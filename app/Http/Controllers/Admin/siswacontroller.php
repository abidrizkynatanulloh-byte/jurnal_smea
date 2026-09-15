<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SiswaController
{
    /**
     * Menampilkan Halaman Data Siswa (Form Tambah Siswa di Atas + Tabel di Bawah).
     */
    public function index(Request $request)
    {
        // 1. Data Kelas untuk Dropdown Pilihan
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        // 2. Query Siswa dengan Search & Filter Kelas
        $query = Siswa::with('kelas');

        if ($request->filled('search')) {
            $keyword = trim($request->search);
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_siswa', 'LIKE', "%{$keyword}%")
                  ->orWhere('nis', 'LIKE', "%{$keyword}%")
                  ->orWhere('nisn', 'LIKE', "%{$keyword}%");
            });
        }

        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        $perPage = (int) $request->input('per_page', 30);
        if ($perPage <= 0) $perPage = 30;

        $siswaList = $query->orderBy('nama_siswa')->paginate($perPage)->withQueryString();
        $totalSiswa = Siswa::count();

        if ($request->ajax()) {
            return response()->json([
                'html'       => view('admin.siswa.partials.rows', compact('siswaList'))->render(),
                'pagination' => view('components.pagination-bar', ['paginator' => $siswaList])->render(),
                'count'      => $siswaList->total(),
            ]);
        }

        return view('admin.siswa.index', compact('siswaList', 'kelasList', 'totalSiswa'));
    }

    /**
     * Menyimpan Siswa Baru + Otomatis Buat Akun Login untuk Wali Murid.
     */
    /**
     * Menyimpan Siswa Baru + Otomatis Buat Akun Login untuk Wali Murid.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn'          => 'required|string|max:20|unique:siswa,nisn|unique:users,username',
            'nis'           => 'nullable|string|max:20',
            'nama_siswa'    => 'required|string|max:100',
            'id_kelas'      => 'required|exists:kelas,id_kelas',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp_wali'    => 'nullable|string|max:25',
        ], [
            'nisn.required'       => 'Nomor Induk Siswa Nasional (NISN) wajib diisi.',
            'nisn.unique'         => 'NISN ini sudah terdaftar.',
            'nama_siswa.required' => 'Nama lengkap siswa wajib diisi.',
            'id_kelas.required'   => 'Pilih kelas siswa.',
        ]);

        DB::beginTransaction();
        try {
            $dataToSave = [
                'nisn'          => $validated['nisn'],
                'nis'           => $validated['nis'] ?: null,
                'nama_siswa'    => $validated['nama_siswa'],
                'id_kelas'      => $validated['id_kelas'],
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? 'L',
                'no_hp_wali'    => $validated['no_hp_wali'] ?? null,
            ];

            if (!Schema::hasColumn('siswa', 'jenis_kelamin')) unset($dataToSave['jenis_kelamin']);
            if (!Schema::hasColumn('siswa', 'no_hp_wali')) unset($dataToSave['no_hp_wali']);

            // 1. Simpan profil siswa
            $siswa = Siswa::create($dataToSave);

            // 2. Buat akun login Wali Murid otomatis (Password default: ortu123)
            User::updateOrCreate(
                ['username' => $validated['nisn']],
                [
                    'password'   => Hash::make('ortu123'),
                    'role'       => 'wali_murid',
                    'nisn_siswa' => $validated['nisn'],
                    'is_active'  => 1,
                ]
            );

            DB::commit();
            return redirect()->route('admin.siswa.index')->with('success', 'Siswa baru berhasil ditambahkan dan akun Wali Murid aktif!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    /**
     * Memperbarui Data Siswa.
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::where('nisn', $id)->orWhere('nis', $id)->firstOrFail();

        $validated = $request->validate([
            'nama_siswa'    => 'required|string|max:100',
            'nisn'          => 'required|string|max:20|unique:siswa,nisn,' . $siswa->nisn . ',nisn',
            'nis'           => 'nullable|string|max:20',
            'id_kelas'      => 'required|exists:kelas,id_kelas',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp_wali'    => 'nullable|string|max:25',
        ]);

        $updateData = [
            'nama_siswa' => $validated['nama_siswa'],
            'nisn'       => $validated['nisn'],
            'nis'        => $validated['nis'] ?: null,
            'id_kelas'   => $validated['id_kelas'],
        ];

        if (Schema::hasColumn('siswa', 'jenis_kelamin') && isset($validated['jenis_kelamin'])) {
            $updateData['jenis_kelamin'] = $validated['jenis_kelamin'];
        }
        if (Schema::hasColumn('siswa', 'no_hp_wali')) {
            $updateData['no_hp_wali'] = $validated['no_hp_wali'] ?? null;
        }

        $siswa->update($updateData);

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    /**
     * Soft Delete Data Siswa.
     */
    public function destroy($id, Request $request)
    {
        $siswa = Siswa::where('nisn', $id)->orWhere('nis', $id)->firstOrFail();
        $alasan = $request->input('alasan_hapus', 'Tanpa Alasan Khusus');
        $siswa->alasan_hapus = $alasan;
        $siswa->save();

        $siswa->delete();

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dipindahkan ke sampah.');
    }

    /**
     * Menampilkan Data Siswa yang Ada di Sampah (Trash).
     */
    public function trash()
    {
        $trashSiswa = Siswa::onlyTrashed()->with('kelas')->paginate(30);
        return view('admin.siswa.trash', compact('trashSiswa'));
    }

    /**
     * Memulihkan Data Siswa dari Sampah (Restore).
     */
    public function restore($id)
    {
        $siswa = Siswa::onlyTrashed()->where('nisn', $id)->orWhere('nis', $id)->firstOrFail();
        $siswa->restore();

        return redirect()->route('admin.siswa.trash')->with('success', 'Data siswa berhasil dipulihkan!');
    }

    /**
     * Download Template CSV untuk Data Siswa.
     */
    public function downloadTemplate()
    {
        $headers = [
            "Content-Type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=template_import_siswa.csv",
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['nis', 'nisn', 'nama_siswa', 'kelas', 'jenis_kelamin', 'no_hp_wali'], ';');
            fputcsv($file, ['23451', '0051234567', 'Ahmad Rizki Pratama', 'X TKL 1', 'L', '081234567890'], ';');
            fputcsv($file, ['23452', '0051234568', 'Siti Rahmawati', 'X TKL 1', 'P', '082198765432'], ';');
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import Data Siswa dari File Excel / CSV (Upsert / Update jika NIS/NISN sudah ada).
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|file|max:10240',
        ], [
            'file_csv.required' => 'File wajib dipilih.',
            'file_csv.max'      => 'Ukuran file maksimal 10MB.',
        ]);

        $file = $request->file('file_csv');
        $rows = $this->parseCsv($file->getRealPath());

        if (empty($rows)) {
            return back()->withErrors(['file_csv' => 'File kosong atau tidak dapat dibaca.']);
        }

        $insertedCount = 0;
        $updatedCount = 0;
        $kelascache = [];

        // Pre-compute default password hash once for all 2000+ students
        $defaultOrtuHash = Hash::make('ortu123');
        $hasJk = Schema::hasColumn('siswa', 'jenis_kelamin');
        $hasNoHpWali = Schema::hasColumn('siswa', 'no_hp_wali');

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                // NISN sebagai identitas utama (primary identifier)
                $nisn = trim($row['nisn'] ?? ($row['nisn_siswa'] ?? ($row[1] ?? ($row[0] ?? ''))));
                $nis  = trim($row['nis'] ?? ($row['no_induk'] ?? ($row['nis_siswa'] ?? ($row[0] ?? ($row[1] ?? '')))));
                $nama = trim($row['nama_siswa'] ?? ($row['nama'] ?? ($row['nama_lengkap'] ?? ($row['siswa'] ?? ($row[2] ?? '')))));
                $kelasInput = trim($row['kelas'] ?? ($row['nama_kelas'] ?? ($row['id_kelas'] ?? ($row[3] ?? ''))));

                if (empty($nama)) {
                    continue;
                }
                if (empty($nisn) && empty($nis)) {
                    continue;
                }
                if (empty($nisn)) {
                    $nisn = $nis;
                }
                if (empty($nis)) {
                    $nis = $nisn;
                }

                $jk = strtoupper(trim($row['jenis_kelamin'] ?? ($row['jk'] ?? ($row['lp'] ?? ($row[4] ?? 'L')))));
                if ($jk !== 'P') $jk = 'L';
                $noHpWali = trim($row['no_hp_wali'] ?? ($row['no_hp'] ?? ($row['hp'] ?? ($row['whatsapp'] ?? ($row[5] ?? '')))));

                // Resolve Kelas ID
                $idKelas = null;
                if (!empty($kelasInput)) {
                    if (isset($kelascache[$kelasInput])) {
                        $idKelas = $kelascache[$kelasInput];
                    } else {
                        $k = Kelas::where('id_kelas', $kelasInput)
                            ->orWhere('nama_kelas', $kelasInput)
                            ->orWhere('nama_kelas', 'LIKE', $kelasInput)
                            ->first();

                        if (!$k) {
                            // Automagically create class if not exists
                            $k = Kelas::create([
                                'nama_kelas' => $kelasInput,
                            ]);
                        }
                        $idKelas = $k->id_kelas;
                        $kelascache[$kelasInput] = $idKelas;
                    }
                }

                if (!$idKelas) {
                    // Default fallback to first class if not specified
                    $firstKelas = Kelas::first();
                    $idKelas = $firstKelas ? $firstKelas->id_kelas : null;
                }

                if (!$idKelas) continue;

                $dataToSave = [
                    'nis'        => $nis,
                    'nisn'       => $nisn,
                    'nama_siswa' => $nama,
                    'id_kelas'   => $idKelas,
                    'deleted_at' => null,
                ];

                if ($hasJk) {
                    $dataToSave['jenis_kelamin'] = $jk;
                }
                if ($hasNoHpWali) {
                    $dataToSave['no_hp_wali'] = $noHpWali ?: null;
                }

                // Cari siswa berdasarkan NIS atau NISN (termasuk soft deleted)
                $existingSiswa = Siswa::withTrashed()
                    ->where('nis', $nis)
                    ->orWhere('nisn', $nisn)
                    ->first();

                if ($existingSiswa) {
                    $updatedCount++;
                    if ($existingSiswa->trashed()) {
                        $existingSiswa->restore();
                    }
                    $existingSiswa->update($dataToSave);
                } else {
                    $insertedCount++;
                    Siswa::create($dataToSave);
                }

                // Create or update Wali Murid account using pre-hashed password
                $existingUser = User::withTrashed()
                    ->where('username', $nisn)
                    ->orWhere('username', $nis)
                    ->first();

                $userData = [
                    'username'   => $nisn,
                    'password'   => $defaultOrtuHash,
                    'role'       => 'wali_murid',
                    'nisn_siswa' => $nisn,
                    'is_active'  => 1,
                    'deleted_at' => null,
                ];

                if ($existingUser) {
                    if ($existingUser->trashed()) {
                        $existingUser->restore();
                    }
                    $existingUser->update($userData);
                } else {
                    User::create($userData);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['file_csv' => 'Gagal mengimpor data: ' . $e->getMessage()]);
        }

        $totalProcessed = $insertedCount + $updatedCount;

        return redirect()->route('admin.siswa.index')->with(
            'success',
            "Import data siswa selesai! Total {$totalProcessed} data siswa berhasil diproses (Data baru: {$insertedCount}, Data diperbarui/dipulihkan: {$updatedCount})."
        );
    }

    /**
     * Parse File Multi-Format (Mendukung XML Excel .xls, HTML Table .xls, CSV, TSV, & BOM UTF-8).
     */
    private function parseCsv($filePath)
    {
        $content = file_get_contents($filePath);
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);

        if (str_contains($content, '<Workbook') || str_contains($content, '<ss:Workbook')) {
            return $this->parseXmlSpreadsheet($content);
        }

        if (str_contains($content, '<table') || str_contains($content, '<TABLE')) {
            return $this->parseHtmlTable($content);
        }

        if (str_starts_with($content, "PK\x03\x04")) {
            return $this->parseXlsxZip($filePath);
        }

        $lines = preg_split('/\r\n|\r|\n/', trim($content));
        if (empty($lines)) return [];

        if (isset($lines[0]) && str_starts_with(strtolower(trim($lines[0])), 'sep=')) {
            array_shift($lines);
        }

        if (empty($lines)) return [];

        $headerLine = $lines[0];
        $delimiter = ',';
        if (substr_count($headerLine, ';') > substr_count($headerLine, ',')) {
            $delimiter = ';';
        } elseif (substr_count($headerLine, "\t") > substr_count($headerLine, ',')) {
            $delimiter = "\t";
        }

        $header = null;
        $rows = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;

            $data = str_getcsv($line, $delimiter);
            $cleanData = array_map(function($val) {
                $v = trim($val);
                if (str_starts_with($v, '="') && str_ends_with($v, '"')) {
                    $v = substr($v, 2, -1);
                }
                return trim($v, "'\"\t ");
            }, $data);

            if (!$header) {
                $header = array_map(function($h) {
                    return strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '', str_replace([' ', '-'], '_', strtolower($h)))));
                }, $cleanData);
            } else {
                $row = [];
                foreach ($header as $index => $col) {
                    $row[$col] = isset($cleanData[$index]) ? trim($cleanData[$index]) : '';
                    $row[$index] = isset($cleanData[$index]) ? trim($cleanData[$index]) : '';
                }
                $rows[] = $row;
            }
        }

        return $rows;
    }

    private function parseXmlSpreadsheet($content)
    {
        $rows = [];
        $header = null;

        preg_match_all('/<Row[^>]*>(.*?)<\/Row>/is', $content, $rowMatches);
        if (empty($rowMatches[1])) return [];

        foreach ($rowMatches[1] as $rowXml) {
            preg_match_all('/<Data[^>]*>(.*?)<\/Data>/is', $rowXml, $dataMatches);
            $rowData = array_map('trim', $dataMatches[1] ?? []);
            if (empty($rowData)) continue;

            if (!$header) {
                $header = array_map(function($h) {
                    return strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '', str_replace([' ', '-'], '_', strtolower($h)))));
                }, $rowData);
            } else {
                $row = [];
                foreach ($header as $index => $col) {
                    $row[$col] = isset($rowData[$index]) ? trim($rowData[$index]) : '';
                    $row[$index] = isset($rowData[$index]) ? trim($rowData[$index]) : '';
                }
                $rows[] = $row;
            }
        }

        return $rows;
    }

    private function parseHtmlTable($content)
    {
        $rows = [];
        $header = null;

        preg_match_all('/<tr[^>]*>(.*?)<\/tr>/is', $content, $trMatches);
        if (empty($trMatches[1])) return [];

        foreach ($trMatches[1] as $trXml) {
            preg_match_all('/<(?:td|th)[^>]*>(.*?)<\/(?:td|th)>/is', $trXml, $cellMatches);
            $cellData = array_map(function($c) {
                return trim(strip_tags($c));
            }, $cellMatches[1] ?? []);

            if (empty($cellData)) continue;

            if (!$header) {
                $header = array_map(function($h) {
                    return strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '', str_replace([' ', '-'], '_', strtolower($h)))));
                }, $cellData);
            } else {
                $row = [];
                foreach ($header as $index => $col) {
                    $row[$col] = isset($cellData[$index]) ? trim($cellData[$index]) : '';
                    $row[$index] = isset($cellData[$index]) ? trim($cellData[$index]) : '';
                }
                $rows[] = $row;
            }
        }

        return $rows;
    }

    private function parseXlsxZip($filePath)
    {
        if (!class_exists('ZipArchive')) {
            return [];
        }

        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== true) {
            return [];
        }

        // 1. Read Shared Strings
        $sharedStrings = [];
        $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedStringsXml) {
            $xml = @simplexml_load_string($sharedStringsXml);
            if ($xml) {
                foreach ($xml->children() as $node) {
                    if ($node->getName() === 'si') {
                        if (isset($node->t)) {
                            $sharedStrings[] = (string) $node->t;
                        } elseif (isset($node->r)) {
                            $t = '';
                            foreach ($node->r as $r) {
                                $t .= (string) $r->t;
                            }
                            $sharedStrings[] = $t;
                        } else {
                            $sharedStrings[] = '';
                        }
                    }
                }
            }
        }

        // 2. Read Sheet1
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if (!$sheetXml) {
            return [];
        }

        $xml = @simplexml_load_string($sheetXml);
        if (!$xml || !isset($xml->sheetData->row)) {
            return [];
        }

        $rows = [];
        $header = null;

        foreach ($xml->sheetData->row as $rowNode) {
            $rowData = [];
            foreach ($rowNode->c as $cell) {
                $type = (string) $cell['t'];
                $val = (string) $cell->v;
                if ($type === 's' && isset($sharedStrings[(int)$val])) {
                    $val = $sharedStrings[(int)$val];
                }
                $rowData[] = trim($val);
            }

            if (empty($rowData) || count(array_filter($rowData)) === 0) continue;

            if (!$header) {
                $header = array_map(function($h) {
                    return strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '', str_replace([' ', '-'], '_', strtolower($h)))));
                }, $rowData);
            } else {
                $row = [];
                foreach ($header as $index => $col) {
                    $row[$col] = isset($rowData[$index]) ? trim($rowData[$index]) : '';
                    $row[$index] = isset($rowData[$index]) ? trim($rowData[$index]) : '';
                }
                $rows[] = $row;
            }
        }

        return $rows;
    }

    /**
     * Hapus Seluruh Data Siswa & Akun Wali Murid di Database (Reset Total).
     */
    public function truncateAll(Request $request)
    {
        DB::beginTransaction();
        try {
            // 1. Hapus permanen seluruh data siswa
            Siswa::withTrashed()->forceDelete();

            // 2. Hapus permanen seluruh akun user role wali_murid
            User::withTrashed()->where('role', 'wali_murid')->forceDelete();

            DB::commit();
            return redirect()->route('admin.siswa.index')->with('success', 'Seluruh data siswa & akun wali murid di database berhasil dikosongkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mengosongkan data siswa: ' . $e->getMessage()]);
        }
    }
}