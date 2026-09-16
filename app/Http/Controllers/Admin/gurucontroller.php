<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class GuruController
{
    /**
     * Menampilkan Halaman Data Guru & Pegawai.
     */
    public function index(Request $request)
    {
        $query = Guru::with('user');

        // Filter Pencarian Nama / NIP
        if ($request->filled('search')) {
            $keyword = trim($request->search);
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_guru', 'LIKE', "%{$keyword}%")
                  ->orWhere('nip', 'LIKE', "%{$keyword}%")
                  ->orWhere('jabatan', 'LIKE', "%{$keyword}%");
            });
        }

        // Filter Jabatan / Role (Pencarian fleksibel di tabel guru, users, & penugasan piket)
        if ($request->filled('jabatan')) {
            $val = trim($request->jabatan);
            $query->where(function ($q) use ($val) {
                if ($val === 'Guru Piket' || $val === 'guru_piket') {
                    $q->where('jabatan', 'LIKE', '%Piket%')
                      ->orWhere('jabatan', 'guru_piket')
                      ->orWhereHas('user', function ($u) {
                          $u->where('role', 'guru_piket');
                      })
                      ->orWhereHas('piketAssignments');
                } elseif ($val === 'Kepala Sekolah' || $val === 'kepala_sekolah') {
                    $q->where('jabatan', 'LIKE', '%Kepala%')
                      ->orWhereHas('user', function ($u) {
                          $u->where('role', 'kepala_sekolah');
                      });
                } elseif (str_contains(strtolower($val), 'wakasis')) {
                    $q->where('jabatan', 'LIKE', '%Wakasis%')
                      ->orWhereHas('user', function ($u) use ($val) {
                          $u->where('role', 'LIKE', '%wakasis%');
                      });
                } else {
                    $q->where('jabatan', 'LIKE', "%{$val}%")
                      ->orWhereHas('user', function ($u) use ($val) {
                          $u->where('role', 'LIKE', "%{$val}%");
                      });
                }
            });
        }

        $perPage = (int) $request->input('per_page', 30);
        if ($perPage <= 0) $perPage = 30;

        $guruList = $query->orderBy('nama_guru')->paginate($perPage)->withQueryString();
        $totalGuru = Guru::count();
        $existingKepsek = User::where('role', 'kepala_sekolah')->with('guru')->first();

        if ($request->ajax()) {
            return response()->json([
                'html'       => view('admin.guru.partials.rows', compact('guruList'))->render(),
                'pagination' => view('components.pagination-bar', ['paginator' => $guruList])->render(),
                'count'      => $guruList->total(),
            ]);
        }

        return view('admin.guru.index', compact('guruList', 'totalGuru', 'existingKepsek'));
    }

    /**
     * Menyimpan Pegawai / Guru Baru + Otomatis Buat Akun Login di tabel users.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip'        => 'required|string|max:18|unique:guru,nip|unique:users,username',
            'nama_guru'  => 'required|string|max:150',
            'no_hp'      => 'nullable|string|max:15',
            'role'       => 'required|in:guru,guru_piket,staf_tu,satpam,kepala_sekolah,wakasis_siswa,wakasis_guru,waka_kurikulum,waka_sdm',
            'password'   => 'required|string|min:4',
        ], [
            'nip.required'       => 'NIP / Kode Pegawai wajib diisi.',
            'nip.unique'         => 'NIP ini sudah terdaftar.',
            'nama_guru.required' => 'Nama lengkap pegawai wajib diisi.',
            'password.required'  => 'Password wajib diisi.',
        ]);

        if ($validated['role'] === 'kepala_sekolah') {
            $existingKepsek = User::where('role', 'kepala_sekolah')->first();
            if ($existingKepsek) {
                return back()->withInput()->withErrors([
                    'role' => 'Jabatan Kepala Sekolah sudah terisi oleh ' . $existingKepsek->nama_display . '. Silakan ubah role atau hapus akun Kepala Sekolah yang lama terlebih dahulu.'
                ]);
            }
        }

        DB::beginTransaction();
        try {
            // 1. Simpan ke tabel guru
            $guru = Guru::create([
                'nip'        => $validated['nip'],
                'nama_guru'  => $validated['nama_guru'],
                'no_hp'      => $validated['no_hp'],
                'kode_mapel' => null, // Mapel tidak diisi di sini (mengikuti penugasan jadwal KBM)
                'jabatan'    => match ($validated['role']) {
                    'kepala_sekolah' => 'Kepala Sekolah',
                    'wakasis_siswa'  => 'Waka Kesiswaan',
                    'waka_kurikulum', 'wakasis_guru' => 'Waka Kurikulum',
                    'waka_sdm'       => 'Waka SDM',
                    'guru_piket'     => 'Guru Piket',
                    default          => 'Guru',
                },
            ]);

            // 2. Otomatis buat akun di tabel users
            User::create([
                'username'  => $validated['nip'],
                'password'  => Hash::make($validated['password']),
                'role'      => $validated['role'],
                'id_guru'   => $guru->id_guru,
                'is_active' => 1,
            ]);

            DB::commit();
            return redirect()->route('admin.guru.index')->with('success', 'Pegawai baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    /**
     * Memperbarui Data Guru & Akun Loginnya.
     */
    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $validated = $request->validate([
            'nip'       => 'required|string|max:18|unique:guru,nip,' . $guru->id_guru . ',id_guru',
            'nama_guru' => 'required|string|max:150',
            'no_hp'     => 'nullable|string|max:15',
            'role'      => 'nullable|in:guru,guru_piket,staf_tu,satpam,kepala_sekolah,wakasis_siswa,wakasis_guru,waka_kurikulum,waka_sdm',
            'password'  => 'nullable|string|min:4',
        ], [
            'nip.required'       => 'NIP / Kode Pegawai wajib diisi.',
            'nip.unique'         => 'NIP ini sudah terdaftar.',
            'nama_guru.required' => 'Nama lengkap pegawai wajib diisi.',
        ]);

        if (isset($validated['role']) && $validated['role'] === 'kepala_sekolah') {
            $existingKepsek = User::where('role', 'kepala_sekolah')
                ->where('id_guru', '!=', $guru->id_guru)
                ->first();
            if ($existingKepsek) {
                return back()->withInput()->withErrors([
                    'role' => 'Jabatan Kepala Sekolah sudah terisi oleh ' . $existingKepsek->nama_display . '. Silakan ubah role atau hapus akun Kepala Sekolah yang lama terlebih dahulu.'
                ]);
            }
        }

        DB::beginTransaction();
        try {
            // Update tabel guru
            $guru->update([
                'nip'       => $validated['nip'],
                'nama_guru' => $validated['nama_guru'],
                'no_hp'     => $validated['no_hp'],
                'jabatan'   => isset($validated['role']) ? match ($validated['role']) {
                    'kepala_sekolah' => 'Kepala Sekolah',
                    'wakasis_siswa'  => 'Waka Kesiswaan',
                    'waka_kurikulum', 'wakasis_guru' => 'Waka Kurikulum',
                    'waka_sdm'       => 'Waka SDM',
                    'guru_piket'     => 'Guru Piket',
                    default          => 'Guru',
                } : $guru->jabatan,
            ]);

            // Update tabel users
            $user = User::where('id_guru', $guru->id_guru)->first();
            if ($user) {
                $userUpdates = ['username' => $validated['nip']];
                if (!empty($validated['role'])) {
                    $userUpdates['role'] = $validated['role'];
                }
                if (!empty($validated['password'])) {
                    $userUpdates['password'] = Hash::make($validated['password']);
                }
                $user->update($userUpdates);
            }

            DB::commit();
            return redirect()->route('admin.guru.index')->with('success', 'Data pegawai berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal memperbarui: ' . $e->getMessage()]);
        }
    }

    /**
     * Soft Delete Data Guru.
     */
    public function destroy($id, Request $request)
    {
        $guru = Guru::findOrFail($id);
        $alasan = $request->input('alasan_hapus', 'Tanpa Alasan Khusus');
        $guru->alasan_hapus = $alasan;
        $guru->save();

        User::where('id_guru', $guru->id_guru)->delete();
        $guru->delete();

        return redirect()->route('admin.guru.index')->with('success', 'Data pegawai berhasil dipindahkan ke sampah.');
    }

    /**
     * Menampilkan Data Guru di Tong Sampah (Trash / Soft Deleted).
     */
    public function trash()
    {
        $trashList = Guru::onlyTrashed()->paginate(30);
        return view('admin.guru.trash', compact('trashList'));
    }

    /**
     * Mengembalikan Data Guru dari Tong Sampah (Restore).
     */
    public function restore($id)
    {
        $guru = Guru::onlyTrashed()->findOrFail($id);
        $guru->restore();
        User::withTrashed()->where('id_guru', $guru->id_guru)->restore();

        return redirect()->route('admin.guru.trash')->with('success', 'Data guru berhasil dipulihkan!');
    }

    /**
     * Download Template CSV untuk Data Guru.
     */
    public function downloadTemplate()
    {
        $headers = [
            "Content-Type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=template_import_guru.csv",
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['nip', 'nama_guru', 'no_hp', 'role', 'password'], ';');
            fputcsv($file, ['198001012005011001', 'Drs. Budi Santoso, M.Pd', '081234567890', 'guru', 'guru123'], ';');
            fputcsv($file, ['198502022008022002', 'Siti Aminah, S.Pd', '082198765432', 'guru_piket', 'guru123'], ';');
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import Data Guru dari File CSV (Upsert / Update jika NIP sudah ada).
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
        $passwordCache = [];

        foreach ($rows as $row) {
            $nip = trim($row['nip'] ?? '');
            $nama = trim($row['nama_guru'] ?? ($row['nama'] ?? ''));

            if (empty($nip) || empty($nama)) {
                continue;
            }

            $noHp = trim($row['no_hp'] ?? ($row['hp'] ?? ($row['whatsapp'] ?? '')));
            $roleRaw = trim($row['role'] ?? ($row['jabatan'] ?? 'guru'));

            $roleClean = match (strtolower($roleRaw)) {
                'kepala sekolah', 'kepala_sekolah', 'kepsek' => 'kepala_sekolah',
                'guru piket', 'guru_piket', 'piket' => 'guru_piket',
                'wakasis siswa', 'wakasis_siswa' => 'wakasis_siswa',
                'wakasis guru', 'wakasis_guru' => 'wakasis_guru',
                'satpam' => 'satpam',
                'staf tu', 'staf_tu', 'tu' => 'staf_tu',
                default => 'guru',
            };

            $jabatan = match ($roleClean) {
                'kepala_sekolah' => 'Kepala Sekolah',
                'wakasis_siswa'  => 'Wakasis Siswa',
                'wakasis_guru'   => 'Wakasis Guru',
                'guru_piket'     => 'Guru Piket',
                'satpam'         => 'Satpam',
                'staf_tu'        => 'Staf TU',
                default          => 'Guru',
            };

            $passwordRaw = !empty($row['password']) ? trim($row['password']) : 'guru123';
            if (!isset($passwordCache[$passwordRaw])) {
                $passwordCache[$passwordRaw] = Hash::make($passwordRaw);
            }

            DB::beginTransaction();
            try {
                $existingGuru = Guru::withTrashed()->where('nip', $nip)->first();
                if ($existingGuru) {
                    $updatedCount++;
                } else {
                    $insertedCount++;
                }

                $guru = Guru::withTrashed()->updateOrCreate(
                    ['nip' => $nip],
                    [
                        'nama_guru' => $nama,
                        'no_hp'     => $noHp ?: null,
                        'jabatan'   => $jabatan,
                        'deleted_at'=> null,
                    ]
                );

                User::withTrashed()->updateOrCreate(
                    ['username' => $nip],
                    [
                        'password'  => $passwordCache[$passwordRaw],
                        'role'      => $roleClean,
                        'id_guru'   => $guru->id_guru,
                        'is_active' => 1,
                        'deleted_at'=> null,
                    ]
                );

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
            }
        }

        return redirect()->route('admin.guru.index')->with(
            'success', 
            "Import data guru selesai! Data baru: {$insertedCount}, Data diperbarui: {$updatedCount}."
        );
    }

    /**
     * Parse File CSV/TXT Multi-Format (Mendukung XML Excel .xls, HTML Table .xls, CSV, TSV, & BOM UTF-8).
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
                }
                $rows[] = $row;
            }
        }

        return $rows;
    }
}