<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Ruangan;

class JadwalSemesterGanjilFullSeeder extends Seeder
{
    public function run()
    {
        $jsonPath = 'C:\\Users\\asus\\.gemini\\antigravity\\brain\\d06ebc62-30b1-4624-b8a8-8d6de065a27a\\scratch\\extracted_jadwal.json';
        if (!file_exists($jsonPath)) {
            $this->command->error("JSON file not found at {$jsonPath}");
            return;
        }

        $items = json_decode(file_get_contents($jsonPath), true);
        $this->command->info("Loaded " . count($items) . " raw schedule entries from JSON.");

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('jadwal')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Maps
        $kelasMap   = Kelas::pluck('id_kelas', 'nama_kelas')->toArray();
        $guruMap    = Guru::pluck('id_guru', 'nama_guru')->toArray();
        $mapelMap   = Mapel::pluck('kode_mapel', 'nama_mapel')->toArray();
        $ruanganMap = Ruangan::pluck('id_ruangan', 'nama_ruangan')->toArray();

        // Custom aliases for teacher names
        $guruAlias = [
            'Endang Ary Handayani S.T., M.Pd' => 'Endang Ary Handayani, S.T., M.Pd',
            'Endang Ary Handayani S.T., M.Pd.' => 'Endang Ary Handayani, S.T., M.Pd',
            'Endang Ary Handayani, S.T., M.Pd.' => 'Endang Ary Handayani, S.T., M.Pd',
            'Lutfia Marsalina, S.Pd.I M.Pd.' => 'Lutfia Marsalina, S.Pd.I, M.Pd.',
            'Lutfia Marsalina, S.Pd.I M.Pd' => 'Lutfia Marsalina, S.Pd.I, M.Pd.',
            'Lutfia Marsalina, S.Pd.I, M.Pd' => 'Lutfia Marsalina, S.Pd.I, M.Pd.',
            'Zainul Arifin,S.Pd' => 'Zainul Arifin, S.Pd',
            'Zainul Arifin, S.Pd.' => 'Zainul Arifin, S.Pd',
            'Widodo S.Pd' => 'Widodo, S.Pd',
            'Yuni Jiastuti S.Pd' => 'Yuni Jiastuti, S.Pd',
            'Badrus Sulaiman, S.Pd.' => 'Badrus Sulaiman, S.Pd.',
            'Badrus Sulaiman, S.Pd' => 'Badrus Sulaiman, S.Pd.',
            'Elyana Frisca Monica S.Pd' => 'Elyana Frisca Monica, S.Pd',
            'Elyana Frisca Monica, S.Pd.' => 'Elyana Frisca Monica, S.Pd',
            'Siswanti Purwaningsih, S.T., M.Pd' => 'Siswanti Purwaningsih, S.T., M.Pd',
            'Siswanti Purwaningsih, S.T.,M.Pd' => 'Siswanti Purwaningsih, S.T., M.Pd',
            'Siti Munawaroh, S.Kom.,M.Pd' => 'Siti Munawaroh, S.Kom.,M.Pd',
            'Siti Munawaroh, S.Kom., M.Pd' => 'Siti Munawaroh, S.Kom.,M.Pd',
            'Siti Munawaroh, S.Kom. M.Pd' => 'Siti Munawaroh, S.Kom.,M.Pd',
            'Sinta Lestari, S.Pd.I' => 'Sinta Lestari, S.Pd.I',
            'Listyana Hartati, S.Kom. M.Pd' => 'Listyana Hartati, S.Kom., M.Pd',
            'Listyana Hartati, S.Kom.,M.Pd' => 'Listyana Hartati, S.Kom., M.Pd',
            'Listyana Hartati, S.Kom., M.Pd' => 'Listyana Hartati, S.Kom., M.Pd',
            'Dian Mawarti S.Pd' => 'Dian Mawarti, S.Pd',
            'Angga Widhy Wirawan S.Pd.,M.Pd' => 'Angga Widhy Wirawan, S.Pd.,M.Pd',
            'Angga Widhy Wirawan, S.Pd.,M.Pd' => 'Angga Widhy Wirawan, S.Pd.,M.Pd',
            'Siti Munawaroh, S.Kom. M.Pd.' => 'Siti Munawaroh, S.Kom.,M.Pd',
            'Retno Widyastuti, S.Pd., M.Pd' => 'Retno Widyastuti, S.Pd., M.Pd',
            'Dwi Rini Manfaati, S.Pd.' => 'Dwi Rini Manfaati, S.Pd',
            'Niken Hari Pratiwi, S.Psi.,M.Pd' => 'Niken Hari Pratiwi, S.Psi., M.Pd',
            'Niken Hari Pratiwi, S.Psi., M.Pd' => 'Niken Hari Pratiwi, S.Psi., M.Pd',
            'Purwati,S.Pd' => 'Purwati, S.Pd',
            'Veronica Damay Rulitasari S.Pd' => 'Veronica Damay Rulitasari, S.Pd',
            'Veronica Damay Rulitasari, S.Pd' => 'Veronica Damay Rulitasari, S.Pd',
            'Veronica Damay Rulitasari,S.Pd' => 'Veronica Damay Rulitasari, S.Pd',
            'Veronica Damay Rulitasari S.Pd.' => 'Veronica Damay Rulitasari, S.Pd',
            'Nishfu Laili,S.Pd' => 'Nishfu Laili, S.Pd',
            'Laili Ermawati, S.Pd.' => 'Laili Ermawati, S.Pd',
            'Purwati, S.Pd.' => 'Purwati, S.Pd',
            'Siti Maisaroh S.Pd' => 'Siti Maisaroh, S.Pd',
            'Shinta Indyar Shanty Susanto, S.Kom' => 'Shinta Indyar Shanty Susanto, S.Kom',
            'Atih Wilupi, S.E, M.Pd' => 'Atih Wilupi, S.E., M.Pd',
            'Agustina Mardika Rini, S.Pd.,M.Pd' => 'Agustina Mardika Rini, S.Pd.,M.Pd',
            'Indayah, S.Pd., M.Pd' => 'Indayah, S.Pd., M.Pd',
            'Luluk Munfarida,S.Pd' => 'Luluk Munfarida, S.Pd',
            'Astra Bella Flamboyan, S.Psi' => 'Astra Bella Flamboyan, S.Psi',
            'Astra Bella Flamboyan S.Psi' => 'Astra Bella Flamboyan, S.Psi',
            'Ary Sunaryo, ST.,M.Pd' => 'Ary Sunaryo, S.T., M.Pd',
            'Ary Sunaryo, ST., M.Pd' => 'Ary Sunaryo, S.T., M.Pd',
            'Ary Sunaryo, S.T., M.Pd' => 'Ary Sunaryo, S.T., M.Pd',
            'Nur Eko Wahyuningsih, S.Pd' => 'Nur Eko Wahyuningsih, S.Pd',
            'Nur Eko Wahyuningsih, S.Pd.' => 'Nur Eko Wahyuningsih, S.Pd',
            'Nur Eko Wahyuningsih,S.Pd' => 'Nur Eko Wahyuningsih, S.Pd',
            'Nur Eko Wahyuningsi h, S.Pd' => 'Nur Eko Wahyuningsih, S.Pd',
            'Sa\'ad Wazis Hiedayat, S.Pd' => 'Sa\'ad Wazis Hiedayat, S.Pd',
            'Agus Muharyanto, M.Pd' => 'Agus Muharyanto, M.Pd',
            'Endik Kuswantoro, S.Kom.,M.T' => 'Endik Kuswantoro, S.Kom., M.T',
            'Endik Kuswantoro, S.Kom. M.T' => 'Endik Kuswantoro, S.Kom., M.T',
            'Endik Kuswantoro, S.Kom., M.T' => 'Endik Kuswantoro, S.Kom., M.T',
            'Winarsih, S.Pd, M.Pd' => 'Winarsih, S.Pd, M.Pd',
            'Winarsih, S.Pd, M.Pd.' => 'Winarsih, S.Pd, M.Pd',
            'Winarsih, S.Pd. M.Pd' => 'Winarsih, S.Pd, M.Pd',
            'Ninik Sriwidayati, S.Pd.,M.Pd' => 'Ninik Sriwidayati, S.Pd., M.Pd',
            'Nur Nastutisari, S.ST.Par.' => 'Nur Nastutisari, S.ST.Par.',
            'Mas\'an Widodo, S.Pd. M.T' => 'Mas\'an Widodo, S.Pd. M.T',
            'Mas\'an Widodo, S.Pd.M.T' => 'Mas\'an Widodo, S.Pd. M.T',
            'Erna Qoriah, S.E.' => 'Erna Qoriah, S.E.',
            'Laili Ermawati, S.Pd' => 'Laili Ermawati, S.Pd',
            'Basuki Sarjono, S.Pd' => 'Basuki Sarjono, S.Pd',
            'Wiwik Yuniarsih, S.Pd' => 'Wiwik Yuniarsih, S.Pd',
            'Muhammad Fajar Assidiqi S.Pd' => 'Muhammad Fajar Assidiqi, S.Pd',
            'Muhammad Fajar Assidiqi, S.Pd' => 'Muhammad Fajar Assidiqi, S.Pd',
        ];

        $inserted = 0;
        foreach ($items as $item) {
            $namaKelas  = trim($item['kelas']);
            $hari       = trim($item['hari']);
            $jamMulai   = (int)$item['jam_mulai'];
            $jamSelesai = (int)$item['jam_selesai'];
            $namaRuang  = trim($item['ruangan']);
            $namaMapel  = trim($item['mapel']);
            $namaGuru   = trim($item['guru']);

            // 1. Resolve Kelas
            if (!isset($kelasMap[$namaKelas])) {
                $k = Kelas::create(['nama_kelas' => $namaKelas]);
                $kelasMap[$namaKelas] = $k->id_kelas;
            }
            $idKelas = $kelasMap[$namaKelas];

            // 2. Resolve Guru
            $targetGuruName = $guruAlias[$namaGuru] ?? $namaGuru;
            if (!isset($guruMap[$targetGuruName])) {
                // Try fuzzy lookup
                $foundGuru = Guru::where('nama_guru', 'LIKE', '%' . $targetGuruName . '%')->first();
                if ($foundGuru) {
                    $guruMap[$targetGuruName] = $foundGuru->id_guru;
                } else {
                    $nipTemp = 'G' . str_pad(abs(crc32($targetGuruName)) % 1000000, 6, '0', STR_PAD_LEFT);
                    $g = Guru::create([
                        'nip'       => $nipTemp,
                        'nama_guru' => $targetGuruName,
                        'jabatan'   => 'Guru',
                    ]);
                    $guruMap[$targetGuruName] = $g->id_guru;
                }
            }
            $idGuru = $guruMap[$targetGuruName];

            // 3. Resolve Mapel
            if (!isset($mapelMap[$namaMapel])) {
                $foundMapel = Mapel::where('nama_mapel', 'LIKE', '%' . $namaMapel . '%')->first();
                if ($foundMapel) {
                    $mapelMap[$namaMapel] = $foundMapel->kode_mapel;
                } else {
                    $kodeTemp = preg_replace('/[^A-Z]/', '', strtoupper($namaMapel));
                    $kodeTemp = substr($kodeTemp, 0, 6);
                    if (empty($kodeTemp) || isset($mapelMap[$kodeTemp])) {
                        $kodeTemp = 'M' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
                    }
                    $m = Mapel::create([
                        'kode_mapel' => $kodeTemp,
                        'nama_mapel' => $namaMapel,
                    ]);
                    $mapelMap[$namaMapel] = $m->kode_mapel;
                }
            }
            $kodeMapel = $mapelMap[$namaMapel];

            // 4. Resolve Ruangan
            if (!isset($ruanganMap[$namaRuang])) {
                $r = Ruangan::create(['nama_ruangan' => $namaRuang]);
                $ruanganMap[$namaRuang] = $r->id_ruangan;
            }
            $idRuangan = $ruanganMap[$namaRuang];

            // Insert into jadwal
            DB::table('jadwal')->insert([
                'id_kelas'    => $idKelas,
                'id_guru'     => $idGuru,
                'id_ruangan'  => $idRuangan,
                'hari'        => $hari,
                'jam_mulai'   => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'kode_mapel'  => $kodeMapel,
            ]);
            $inserted++;
        }

        $this->command->info("SUCCESS! Seeded {$inserted} schedule records from PDF into database.");
    }
}
