<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Ruangan;

class JadwalController
{
    public function index(Request $request)
    {
        $kelasListQuery = Kelas::orderBy('nama_kelas');
        
        $tingkat = $request->input('tingkat');
        if ($request->filled('tingkat') && in_array($request->tingkat, ['X', 'XI', 'XII'])) {
            $kelasListQuery->where(function ($k) use ($tingkat) {
                $k->where('nama_kelas', 'like', $tingkat . ' %')
                  ->orWhere('nama_kelas', 'like', $tingkat . '-%')
                  ->orWhere('nama_kelas', 'like', $tingkat . '.%');
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $kelasListQuery->where('nama_kelas', 'like', "%{$search}%");
        }

        $kelasList = $kelasListQuery->get();
        $allKelasList = Kelas::orderBy('nama_kelas')->get();
        $guruList = Guru::orderBy('nama_guru')->get();
        $mapelList = Mapel::orderBy('nama_mapel')->get();
        $ruanganList = Ruangan::orderBy('nama_ruangan')->get();

        // Determinasikan Hari Ini
        $hariMap = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'
        ];
        $namaHariIni = $hariMap[date('l')] ?? 'Senin';
        $validHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $defaultHari = in_array($namaHariIni, $validHari) ? $namaHariIni : 'Senin';

        // Tentukan filter hari (default hari ini jika tidak diset di query string)
        if (!$request->has('hari')) {
            $hariFilter = $defaultHari;
        } else {
            $hariFilter = $request->input('hari') === 'all' ? '' : $request->input('hari');
        }

        $query = Jadwal::with(['kelas', 'guru', 'mapel', 'ruangan']);

        if (!empty($hariFilter)) {
            $query->where('hari', $hariFilter);
        }

        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if (!empty($tingkat)) {
            $query->whereHas('kelas', function ($k) use ($tingkat) {
                $k->where('nama_kelas', 'like', $tingkat . ' %')
                  ->orWhere('nama_kelas', 'like', $tingkat . '-%')
                  ->orWhere('nama_kelas', 'like', $tingkat . '.%');
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('kelas', fn($k) => $k->where('nama_kelas', 'like', "%{$search}%"))
                  ->orWhereHas('guru', fn($g) => $g->where('nama_guru', 'like', "%{$search}%"))
                  ->orWhereHas('mapel', fn($m) => $m->where('nama_mapel', 'like', "%{$search}%"));
            });
        }

        $perPage = (int) $request->input('per_page', 50);
        if ($perPage <= 0) $perPage = 50;

        $jadwalList = $query->orderBy('hari')->orderBy('jam_mulai')->paginate($perPage)->withQueryString();

        // Pre-fetch all schedules for grid view to group by kelas
        $gridQuery = Jadwal::with(['kelas', 'guru', 'mapel', 'ruangan']);
        if (!empty($hariFilter)) {
            $gridQuery->where('hari', $hariFilter);
        }
        if (!empty($tingkat)) {
            $gridQuery->whereHas('kelas', function ($k) use ($tingkat) {
                $k->where('nama_kelas', 'like', $tingkat . ' %')
                  ->orWhere('nama_kelas', 'like', $tingkat . '-%')
                  ->orWhere('nama_kelas', 'like', $tingkat . '.%');
            });
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $gridQuery->where(function ($q) use ($search) {
                $q->whereHas('kelas', fn($k) => $k->where('nama_kelas', 'like', "%{$search}%"))
                  ->orWhereHas('guru', fn($g) => $g->where('nama_guru', 'like', "%{$search}%"))
                  ->orWhereHas('mapel', fn($m) => $m->where('nama_mapel', 'like', "%{$search}%"));
            });
        }
        $allGridJadwal = $gridQuery->orderBy('jam_mulai')->get();
        $jadwalGroupedByKelas = $allGridJadwal->groupBy('id_kelas');

        return view('admin.jadwal.index', compact(
            'jadwalList', 'kelasList', 'allKelasList', 'guruList', 'mapelList', 'ruanganList', 
            'tingkat', 'namaHariIni', 'defaultHari', 'hariFilter', 'jadwalGroupedByKelas'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kelas'    => 'required|exists:kelas,id_kelas',
            'id_guru'     => 'required|exists:guru,id_guru',
            'id_ruangan'  => 'required|exists:ruangan,id_ruangan',
            'kode_mapel'  => 'required|exists:mapel,kode_mapel',
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_mulai'   => 'required|integer|min:1|max:15',
            'jam_selesai' => 'required|integer|gte:jam_mulai|max:15',
        ]);

        Jadwal::create($validated);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal pelajaran berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        Jadwal::findOrFail($id)->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    /**
     * Download Template CSV untuk Data Jadwal.
     */
    public function downloadTemplate()
    {
        $headers = [
            "Content-Type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=template_import_jadwal.csv",
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['kelas', 'hari', 'jam_mulai', 'jam_selesai', 'mapel', 'guru', 'ruangan'], ';');
            fputcsv($file, ['X TKL 1', 'Senin', '1', '2', 'K-TKJ-01', 'Drs. Budi Santoso, M.Pd', 'Lab Komputer 1'], ';');
            fputcsv($file, ['X TKL 1', 'Senin', '3', '4', 'MTK-01', '198502022008022002', 'R. 101'], ';');
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import Data Jadwal KBM dari File Excel / CSV.
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

        foreach ($rows as $row) {
            $kelasInput  = trim($row['kelas'] ?? ($row['nama_kelas'] ?? ($row['id_kelas'] ?? '')));
            $hari        = ucfirst(strtolower(trim($row['hari'] ?? '')));
            $jamMulai    = (int) ($row['jam_mulai'] ?? 1);
            $jamSelesai  = (int) ($row['jam_selesai'] ?? $jamMulai);
            $mapelInput  = trim($row['mapel'] ?? ($row['kode_mapel'] ?? ($row['nama_mapel'] ?? '')));
            $guruInput   = trim($row['guru'] ?? ($row['nip_guru'] ?? ($row['nama_guru'] ?? ($row['id_guru'] ?? ''))));
            $ruangInput  = trim($row['ruangan'] ?? ($row['nama_ruangan'] ?? ($row['id_ruangan'] ?? '')));

            if (empty($kelasInput) || empty($hari) || empty($mapelInput) || empty($guruInput)) {
                continue;
            }

            // Standardize Hari
            $validHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
            if (!in_array($hari, $validHari)) {
                continue;
            }

            // 1. Resolve Kelas
            $kelas = Kelas::where('id_kelas', $kelasInput)
                ->orWhere('nama_kelas', $kelasInput)
                ->orWhere('nama_kelas', 'LIKE', $kelasInput)
                ->first();
            if (!$kelas) {
                $kelas = Kelas::create(['nama_kelas' => $kelasInput]);
            }
            $idKelas = $kelas->id_kelas;

            // 2. Resolve Mapel
            $mapelInputClean = strtoupper(trim($mapelInput));
            $mapel = Mapel::where('kode_mapel', $mapelInput)
                ->orWhere('kode_mapel', $mapelInputClean)
                ->orWhere('nama_mapel', $mapelInput)
                ->orWhere('nama_mapel', 'LIKE', $mapelInput)
                ->first();

            if (!$mapel) {
                $aliasMap = [
                    'MTK' => 'MTK', 'MATEMATIKA' => 'MTK',
                    'BIND' => 'BIND', 'B. INDONESIA' => 'BIND', 'BAHASA INDONESIA' => 'BIND',
                    'BING' => 'BING', 'B. INGGRIS' => 'BING', 'BAHASA INGGRIS' => 'BING',
                    'PAI' => 'PAIBP', 'PAIBP' => 'PAIBP', 'PAI & BP' => 'PAIBP',
                    'PJOK' => 'PJOK', 'PENJAS' => 'PJOK',
                    'PPKN' => 'PPKN', 'PANCASILA' => 'PPKN',
                    'IPAS' => 'IPAS', 'INF' => 'INF', 'INFORMATIKA' => 'INF',
                    'SEJ' => 'SEJ', 'SEJARAH' => 'SEJ',
                    'BJAW' => 'BJAW', 'B. JAWA' => 'BJAW', 'BAHASA JAWA' => 'BJAW',
                ];

                if (isset($aliasMap[$mapelInputClean])) {
                    $targetCode = $aliasMap[$mapelInputClean];
                    $mapel = Mapel::where('kode_mapel', $targetCode)->first();
                }
            }

            if (!$mapel) {
                // If mapel doesn't exist, create automatically with clean singkatan/code
                $kodeMapelAuto = strtoupper(preg_replace('/[^a-zA-Z0-9\-]/', '', str_replace(' ', '-', $mapelInput)));
                if (strlen($kodeMapelAuto) > 25) {
                    $kodeMapelAuto = substr($kodeMapelAuto, 0, 25);
                }
                if (empty($kodeMapelAuto)) {
                    $kodeMapelAuto = 'MPL-' . rand(100, 999);
                }

                $mapel = Mapel::firstOrCreate(
                    ['kode_mapel' => $kodeMapelAuto],
                    ['nama_mapel' => $mapelInput]
                );
            }
            $kodeMapel = $mapel->kode_mapel;

            // 3. Resolve Guru
            $guru = Guru::where('id_guru', $guruInput)
                ->orWhere('nip', $guruInput)
                ->orWhere('nama_guru', $guruInput)
                ->orWhere('nama_guru', 'LIKE', "%{$guruInput}%")
                ->first();
            if (!$guru) {
                continue; // Cannot assign schedule without a valid teacher
            }
            $idGuru = $guru->id_guru;

            // 4. Resolve Ruangan (Optional)
            $idRuangan = null;
            if (!empty($ruangInput)) {
                $ruangan = Ruangan::where('id_ruangan', $ruangInput)
                    ->orWhere('nama_ruangan', $ruangInput)
                    ->orWhere('nama_ruangan', 'LIKE', $ruangInput)
                    ->first();
                if (!$ruangan) {
                    $ruangan = Ruangan::create([
                        'nama_ruangan' => $ruangInput,
                        'jenis_ruangan' => 'Teori'
                    ]);
                }
                $idRuangan = $ruangan->id_ruangan;
            }

            // Upsert Jadwal
            $existingJadwal = Jadwal::where('id_kelas', $idKelas)
                ->where('hari', $hari)
                ->where('jam_mulai', $jamMulai)
                ->first();

            if ($existingJadwal) {
                $updatedCount++;
            } else {
                $insertedCount++;
            }

            Jadwal::updateOrCreate(
                [
                    'id_kelas'  => $idKelas,
                    'hari'      => $hari,
                    'jam_mulai' => $jamMulai,
                ],
                [
                    'jam_selesai' => $jamSelesai,
                    'kode_mapel'  => $kodeMapel,
                    'id_guru'     => $idGuru,
                    'id_ruangan'  => $idRuangan,
                ]
            );
        }

        return redirect()->route('admin.jadwal.index')->with(
            'success',
            "Import jadwal KBM selesai! Jadwal baru: {$insertedCount}, Jadwal diperbarui: {$updatedCount}."
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

    private function parseXlsxZip($filePath)
    {
        if (!class_exists('ZipArchive')) {
            return [];
        }

        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== true) {
            return [];
        }

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
}