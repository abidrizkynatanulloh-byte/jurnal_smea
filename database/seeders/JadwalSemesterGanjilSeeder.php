<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Ruangan;

class JadwalSemesterGanjilSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('jadwal')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Maps
        $kelasMap = Kelas::pluck('id_kelas', 'nama_kelas')->toArray();
        $guruMap  = Guru::pluck('id_guru', 'nama_guru')->toArray();
        $mapelMap = Mapel::pluck('kode_mapel', 'nama_mapel')->toArray();
        $ruanganMap = Ruangan::pluck('id_ruangan', 'nama_ruangan')->toArray();

        // Custom aliases/fallbacks
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

        $rawJadwal = [
            // Page 1: X TKI 1
            ['X TKI 1', 'Senin', 2, 3, 'Muto\'atul Khosi\'ah, S.Pd', 'Bahasa Inggris', 'Lab. KI 1'],
            ['X TKI 1', 'Senin', 4, 6, 'Ilham Sungeidi, S.Pd', 'PJOK', 'Lapangan'],
            ['X TKI 1', 'Senin', 7, 8, 'Yani, S.Pd.', 'Bahasa Indonesia', 'Lab. KI 1'],
            ['X TKI 1', 'Senin', 9, 10, 'Yustin Febrini, S.Pd', 'Bahasa Jawa', 'Lab. KI 1'],
            ['X TKI 1', 'Selasa', 1, 2, 'Anang Prasetyo, S.Pd', 'Seni Budaya', 'Lab. KI 1'],
            ['X TKI 1', 'Selasa', 3, 4, 'Yani, S.Pd.', 'Bahasa Indonesia', 'Lab. KI 1'],
            ['X TKI 1', 'Selasa', 5, 7, 'Khuriyatul Kamila, S.Si', 'IPAS', 'Lab. KI 1'],
            ['X TKI 1', 'Selasa', 8, 10, 'Rifkotin Na\'imah, S.Pd', 'Dasar TKI', 'Lab. KI 1'],
            ['X TKI 1', 'Rabu', 1, 3, 'Endang Ary Handayani, S.T., M.Pd', 'Koding dan Kecerdasan Artifisial', 'Lab. TKJ 3'],
            ['X TKI 1', 'Rabu', 4, 6, 'Sri Kusumastuti, S.Pd', 'Dasar TKI', 'Lab. KI 1'],
            ['X TKI 1', 'Rabu', 7, 8, 'Muashofah, M.Pd', 'Pendidikan Agama Islam dan Budi Pekerti', 'Lab. KI 1'],
            ['X TKI 1', 'Rabu', 9, 10, 'Lutfia Marsalina, S.Pd.I, M.Pd.', 'Informatika', 'Lab. Broadcast'],
            ['X TKI 1', 'Kamis', 1, 2, 'Arvia Rienetasary, S.Pd', 'Matematika', 'Lab. KI 1'],
            ['X TKI 1', 'Kamis', 3, 4, 'Wiwik Yuniarsih, S.Pd', 'Pendidikan Pancasila', 'Lab. KI 1'],
            ['X TKI 1', 'Kamis', 5, 7, 'Khuriyatul Kamila, S.Si', 'IPAS', 'Lab. KI 1'],
            ['X TKI 1', 'Kamis', 8, 10, 'Sri Kusumastuti, S.Pd', 'Dasar TKI', 'Lab. KI 1'],
            ['X TKI 1', 'Jumat', 2, 4, 'Rifkotin Na\'imah, S.Pd', 'Dasar TKI', 'Lab. KI 1'],
            ['X TKI 1', 'Jumat', 5, 5, 'Yuni Jiastuti, S.Pd', 'BK', 'Lab. KI 1'],
            ['X TKI 1', 'Jumat', 6, 7, 'Arvia Rienetasary, S.Pd', 'Matematika', 'Lab. KI 1'],
            ['X TKI 1', 'Jumat', 8, 9, 'Lutfia Marsalina, S.Pd.I, M.Pd.', 'Informatika', 'Lab. Broadcast'],
            ['X TKI 1', 'Jumat', 10, 11, 'Fajar Luthfianto, S.Pd', 'Sejarah', 'Lab. KI 1'],
            ['X TKI 1', 'Jumat', 12, 13, 'Muto\'atul Khosi\'ah, S.Pd', 'Bahasa Inggris', 'Lab. KI 1'],

            // Page 2: X TKI 2
            ['X TKI 2', 'Senin', 2, 3, 'Yani, S.Pd.', 'Bahasa Indonesia', 'R 10'],
            ['X TKI 2', 'Senin', 4, 5, 'Arvia Rienetasary, S.Pd', 'Matematika', 'R 10'],
            ['X TKI 2', 'Senin', 6, 7, 'Wiwik Yuniarsih, S.Pd', 'Pendidikan Pancasila', 'R 10'],
            ['X TKI 2', 'Senin', 8, 10, 'Sri Kusumastuti, S.Pd', 'Dasar TKI', 'R 10'],
            ['X TKI 2', 'Selasa', 1, 1, 'Yuni Jiastuti, S.Pd', 'BK', 'R 10'],
            ['X TKI 2', 'Selasa', 2, 4, 'Rifkotin Na\'imah, S.Pd', 'Dasar TKI', 'R 10'],
            ['X TKI 2', 'Selasa', 5, 6, 'Lutfia Marsalina, S.Pd.I, M.Pd.', 'Informatika', 'Lab. Broadcast'],
            ['X TKI 2', 'Selasa', 7, 7, 'Ista Nofasari, S.Pd', 'Sejarah', 'R 10'],
            ['X TKI 2', 'Selasa', 8, 10, 'Endang Ary Handayani, S.T., M.Pd', 'Koding dan Kecerdasan Artifisial', 'Lab. TKJ 3'],
            ['X TKI 2', 'Rabu', 1, 2, 'Arvia Rienetasary, S.Pd', 'Matematika', 'R 10'],
            ['X TKI 2', 'Rabu', 3, 4, 'Anang Prasetyo, S.Pd', 'Seni Budaya', 'R 10'],
            ['X TKI 2', 'Rabu', 5, 6, 'Fitria Renytasari, S.Pd', 'Bahasa Inggris', 'R 10'],
            ['X TKI 2', 'Rabu', 7, 8, 'Lutfia Marsalina, S.Pd.I, M.Pd.', 'Informatika', 'Lab. Broadcast'],
            ['X TKI 2', 'Rabu', 9, 10, 'Yustin Febrini, S.Pd', 'Bahasa Jawa', 'R 10'],
            ['X TKI 2', 'Kamis', 1, 3, 'Rifkotin Na\'imah, S.Pd', 'Dasar TKI', 'R 10'],
            ['X TKI 2', 'Kamis', 4, 5, 'Fitria Renytasari, S.Pd', 'Bahasa Inggris', 'R 10'],
            ['X TKI 2', 'Kamis', 6, 7, 'Yani, S.Pd.', 'Bahasa Indonesia', 'R 10'],
            ['X TKI 2', 'Kamis', 8, 10, 'Khuriyatul Kamila, S.Si', 'IPAS', 'R 10'],
            ['X TKI 2', 'Jumat', 2, 4, 'Mufatiroh, S.Ag', 'Pendidikan Agama Islam dan Budi Pekerti', 'R 10'],
            ['X TKI 2', 'Jumat', 5, 7, 'Ilham Sungeidi, S.Pd', 'PJOK', 'Lapangan'],
            ['X TKI 2', 'Jumat', 8, 10, 'Sri Kusumastuti, S.Pd', 'Dasar TKI', 'R 10'],
            ['X TKI 2', 'Jumat', 11, 13, 'Khuriyatul Kamila, S.Si', 'IPAS', 'R 10'],

            // Page 3: X RPL 1
            ['X RPL 1', 'Senin', 2, 3, 'Winartin, S.Pd', 'Bahasa Indonesia', 'Lab. RPL 1'],
            ['X RPL 1', 'Senin', 4, 4, 'Widodo, S.Pd', 'BK', 'Lab. RPL 1'],
            ['X RPL 1', 'Senin', 5, 7, 'Zainul Arifin, S.Pd', 'PJOK', 'Lapangan'],
            ['X RPL 1', 'Senin', 8, 10, 'Indriati, S.Pd', 'IPAS', 'R 57'],
            ['X RPL 1', 'Selasa', 1, 3, 'Indriati, S.Pd', 'IPAS', 'R 57'],
            ['X RPL 1', 'Selasa', 4, 5, 'Rizki Putri Wulandari, S.Pd', 'Bahasa Jawa', 'R 57'],
            ['X RPL 1', 'Selasa', 6, 7, 'Fitria Renytasari, S.Pd', 'Bahasa Inggris', 'R 57'],
            ['X RPL 1', 'Selasa', 8, 10, 'Mufatiroh, S.Ag', 'Pendidikan Agama Islam dan Budi Pekerti', 'R 57'],
            ['X RPL 1', 'Rabu', 1, 2, 'Anang Prasetyo, S.Pd', 'Seni Budaya', 'R 57'],
            ['X RPL 1', 'Rabu', 3, 4, 'Elysa Yuli Nur\'aini, S.Si', 'Matematika', 'R 57'],
            ['X RPL 1', 'Rabu', 5, 6, 'Kurnila Putri Islamawati, S.Kom', 'Informatika', 'Lab. RPL 1'],
            ['X RPL 1', 'Rabu', 7, 10, 'Badrus Sulaiman, S.Pd.', 'Dasar PPLG', 'Lab. RPL 1'],
            ['X RPL 1', 'Kamis', 1, 2, 'Fitria Renytasari, S.Pd', 'Bahasa Inggris', 'R 57'],
            ['X RPL 1', 'Kamis', 3, 4, 'Elysa Yuli Nur\'aini, S.Si', 'Matematika', 'R 57'],
            ['X RPL 1', 'Kamis', 5, 6, 'Yustin Febrini, S.Pd', 'Sejarah', 'R 57'],
            ['X RPL 1', 'Kamis', 7, 10, 'Ruly Dwi Setyaningrum, S.Kom', 'Dasar PPLG', 'Lab. RPL 1'],
            ['X RPL 1', 'Jumat', 2, 5, 'Ruly Dwi Setyaningrum, S.Kom', 'Dasar PPLG', 'Lab. RPL 1'],
            ['X RPL 1', 'Jumat', 6, 7, 'Elyana Frisca Monica, S.Pd', 'Koding dan Kecerdasan Artifisial', 'Lab. RPL 1'],
            ['X RPL 1', 'Jumat', 8, 9, 'Abdul Rohman, S.Pd', 'Pendidikan Pancasila', 'R 57'],
            ['X RPL 1', 'Jumat', 10, 11, 'Kurnila Putri Islamawati, S.Kom', 'Informatika', 'Lab. RPL 1'],
            ['X RPL 1', 'Jumat', 12, 13, 'Winartin, S.Pd', 'Bahasa Indonesia', 'Lab. RPL 1'],

            // Page 4: X RPL 2
            ['X RPL 2', 'Senin', 2, 5, 'Ruly Dwi Setyaningrum, S.Kom', 'Dasar PPLG', 'Lab. RPL 2'],
            ['X RPL 2', 'Senin', 6, 7, 'Umi Kulsum, S.Pd', 'Bahasa Indonesia', 'R 57'],
            ['X RPL 2', 'Senin', 8, 10, 'Fitri Amaliyah, S.Pd', 'IPAS', 'R 58'],
            ['X RPL 2', 'Selasa', 1, 4, 'Elyana Frisca Monica, S.Pd', 'Informatika', 'Lab. RPL 2'],
            ['X RPL 2', 'Selasa', 5, 6, 'Elyana Frisca Monica, S.Pd', 'Koding dan Kecerdasan Artifisial', 'Lab. RPL 2'],
            ['X RPL 2', 'Selasa', 7, 8, 'Yustin Febrini, S.Pd', 'Sejarah', 'R 58'],
            ['X RPL 2', 'Selasa', 9, 10, 'Abdul Rohman, S.Pd', 'Pendidikan Pancasila', 'R 58'],
            ['X RPL 2', 'Rabu', 1, 2, 'Fajar Wahyu Pratiwi, S.S', 'Bahasa Inggris', 'R 58'],
            ['X RPL 2', 'Rabu', 3, 4, 'Fajar Wahyu Pratiwi, S.S', 'Bahasa Inggris', 'R 58'],
            ['X RPL 2', 'Rabu', 5, 6, 'Elysa Yuli Nur\'aini, S.Si', 'Matematika', 'R 58'],
            ['X RPL 2', 'Rabu', 7, 10, 'Ruly Dwi Setyaningrum, S.Kom', 'Dasar PPLG', 'Lab. RPL 2'],
            ['X RPL 2', 'Kamis', 1, 4, 'Badrus Sulaiman, S.Pd.', 'Dasar PPLG', 'Lab. RPL 2'],
            ['X RPL 2', 'Kamis', 5, 7, 'Mufatiroh, S.Ag', 'Pendidikan Agama Islam dan Budi Pekerti', 'R 58'],
            ['X RPL 2', 'Kamis', 8, 10, 'Fitri Amaliyah, S.Pd', 'IPAS', 'R 58'],
            ['X RPL 2', 'Jumat', 2, 3, 'Umi Kulsum, S.Pd', 'Bahasa Indonesia', 'Lab. RPL 2'],
            ['X RPL 2', 'Jumat', 4, 4, 'Widodo, S.Pd', 'BK', 'Lab. RPL 2'],
            ['X RPL 2', 'Jumat', 5, 7, 'Zainul Arifin, S.Pd', 'PJOK', 'Lapangan'],
            ['X RPL 2', 'Jumat', 8, 9, 'Laili Ermawati, S.Pd', 'Bahasa Jawa', 'R 58'],
            ['X RPL 2', 'Jumat', 10, 11, 'Anang Prasetyo, S.Pd', 'Seni Budaya', 'R 58'],
            ['X RPL 2', 'Jumat', 12, 13, 'Elysa Yuli Nur\'aini, S.Si', 'Matematika', 'R 58'],

            // Page 5: X TKJ 1
            ['X TKJ 1', 'Senin', 2, 4, 'Fitri Amaliyah, S.Pd', 'IPAS', 'R 32'],
            ['X TKJ 1', 'Senin', 5, 6, 'Isti Mufadah, S.Pd', 'Bahasa Inggris', 'R 32'],
            ['X TKJ 1', 'Senin', 7, 8, 'Listyana Hartati, S.Kom., M.Pd', 'Koding dan Kecerdasan Artifisial', 'Lab. TKJ 3'],
            ['X TKJ 1', 'Senin', 9, 10, 'Listyana Hartati, S.Kom., M.Pd', 'Dasar TJKT', 'Lab. TKJ 3'],
            ['X TKJ 1', 'Selasa', 1, 4, 'Siswanti Purwaningsih, S.T., M.Pd', 'Informatika', 'Lab. TKJ 3'],
            ['X TKJ 1', 'Selasa', 5, 7, 'Fitri Amaliyah, S.Pd', 'IPAS', 'R 32'],
            ['X TKJ 1', 'Selasa', 8, 9, 'Muhammad Fajar Assidiqi, S.Pd', 'Bahasa Jawa', 'R 32'],
            ['X TKJ 1', 'Selasa', 10, 10, 'Nishfu Laili, S.Pd', 'BK', 'R 32'],
            ['X TKJ 1', 'Rabu', 1, 2, 'Basuki Sarjono, S.Pd', 'Matematika', 'R 32'],
            ['X TKJ 1', 'Rabu', 3, 4, 'Sri Rahayu, S.Pd', 'Bahasa Indonesia', 'R 32'],
            ['X TKJ 1', 'Rabu', 5, 6, 'Endang Ary Handayani, S.T., M.Pd', 'Dasar TJKT', 'Lab. TKJ 3'],
            ['X TKJ 1', 'Rabu', 7, 8, 'Basuki Sarjono, S.Pd', 'Matematika', 'R 32'],
            ['X TKJ 1', 'Rabu', 9, 10, 'Isti Mufadah, S.Pd', 'Bahasa Inggris', 'R 32'],
            ['X TKJ 1', 'Kamis', 1, 2, 'Ista Nofasari, S.Pd', 'Sejarah', 'R 32'],
            ['X TKJ 1', 'Kamis', 3, 4, 'Sri Rahayu, S.Pd', 'Bahasa Indonesia', 'R 32'],
            ['X TKJ 1', 'Kamis', 5, 6, 'Wiwik Yuniarsih, S.Pd', 'Pendidikan Pancasila', 'R 32'],
            ['X TKJ 1', 'Kamis', 7, 10, 'Siti Munawaroh, S.Kom.,M.Pd', 'Dasar TJKT', 'Lab. TKJ 3'],
            ['X TKJ 1', 'Jumat', 2, 4, 'Ilham Sungeidi, S.Pd', 'PJOK', 'Lapangan'],
            ['X TKJ 1', 'Jumat', 5, 6, 'Anang Prasetyo, S.Pd', 'Seni Budaya', 'R 32'],
            ['X TKJ 1', 'Jumat', 7, 9, 'Siswanti Purwaningsih, S.T., M.Pd', 'Dasar TJKT', 'Lab. TKJ 3'],
            ['X TKJ 1', 'Jumat', 10, 13, 'Sinta Lestari, S.Pd.I', 'Pendidikan Agama Islam dan Budi Pekerti', 'R 32'],

            // Page 6: X TKJ 2
            ['X TKJ 2', 'Senin', 2, 5, 'Siti Munawaroh, S.Kom.,M.Pd', 'Dasar TJKT', 'Lab. TKJ 3'],
            ['X TKJ 2', 'Senin', 6, 8, 'Sinta Lestari, S.Pd.I', 'Pendidikan Agama Islam dan Budi Pekerti', 'R 33'],
            ['X TKJ 2', 'Senin', 9, 10, 'Basuki Sarjono, S.Pd', 'Matematika', 'R 33'],
            ['X TKJ 2', 'Selasa', 1, 2, 'Sri Rahayu, S.Pd', 'Bahasa Indonesia', 'R 33'],
            ['X TKJ 2', 'Selasa', 3, 5, 'Ilham Sungeidi, S.Pd', 'PJOK', 'Lapangan'],
            ['X TKJ 2', 'Selasa', 6, 7, 'Endang Ary Handayani, S.T., M.Pd', 'Dasar TJKT', 'Lab. TKJ 3'],
            ['X TKJ 2', 'Selasa', 8, 10, 'Fitri Amaliyah, S.Pd', 'IPAS', 'R 33'],
            ['X TKJ 2', 'Rabu', 1, 2, 'Wiwik Yuniarsih, S.Pd', 'Pendidikan Pancasila', 'R 33'],
            ['X TKJ 2', 'Rabu', 3, 3, 'Nishfu Laili, S.Pd', 'BK', 'R 33'],
            ['X TKJ 2', 'Rabu', 4, 6, 'Fitri Amaliyah, S.Pd', 'IPAS', 'R 33'],
            ['X TKJ 2', 'Rabu', 7, 8, 'Listyana Hartati, S.Kom., M.Pd', 'Dasar TJKT', 'Lab. TKJ 3'],
            ['X TKJ 2', 'Rabu', 9, 10, 'Basuki Sarjono, S.Pd', 'Matematika', 'R 33'],
            ['X TKJ 2', 'Kamis', 1, 4, 'Siswanti Purwaningsih, S.T., M.Pd', 'Informatika', 'Lab. TKJ 3'],
            ['X TKJ 2', 'Kamis', 5, 6, 'Sri Rahayu, S.Pd', 'Bahasa Indonesia', 'R 33'],
            ['X TKJ 2', 'Kamis', 7, 8, 'Anang Prasetyo, S.Pd', 'Seni Budaya', 'R 33'],
            ['X TKJ 2', 'Kamis', 9, 10, 'Isti Mufadah, S.Pd', 'Bahasa Inggris', 'R 33'],
            ['X TKJ 2', 'Jumat', 2, 4, 'Siswanti Purwaningsih, S.T., M.Pd', 'Dasar TJKT', 'Lab. TKJ 3'],
            ['X TKJ 2', 'Jumat', 5, 7, 'Yustin Febrini, S.Pd', 'Sejarah', 'R 33'],
            ['X TKJ 2', 'Jumat', 8, 9, 'Muhammad Fajar Assidiqi, S.Pd', 'Bahasa Jawa', 'R 33'],
            ['X TKJ 2', 'Jumat', 10, 11, 'Isti Mufadah, S.Pd', 'Bahasa Inggris', 'R 33'],
            ['X TKJ 2', 'Jumat', 12, 13, 'Listyana Hartati, S.Kom., M.Pd', 'Koding dan Kecerdasan Artifisial', 'Lab. TKJ 3'],

            // Page 7: X BD 1
            ['X BD 1', 'Senin', 2, 3, 'Pipit Ambarwati, S.Pd', 'Sejarah', 'R 23'],
            ['X BD 1', 'Senin', 4, 7, 'Ratih Dian Irawati, SE', 'Dasar PM', 'R 23'],
            ['X BD 1', 'Senin', 8, 9, 'Arif Setyobudi, S.Pd', 'Bahasa Indonesia', 'R 23'],
            ['X BD 1', 'Senin', 10, 10, 'Dian Mawarti, S.Pd', 'BK', 'R 23'],
            ['X BD 1', 'Selasa', 1, 3, 'Sinta Lestari, S.Pd.I', 'Pendidikan Agama Islam dan Budi Pekerti', 'R 23'],
            ['X BD 1', 'Selasa', 4, 5, 'Arif Setyobudi, S.Pd', 'Bahasa Indonesia', 'R 23'],
            ['X BD 1', 'Selasa', 6, 7, 'Indriati, S.Pd', 'IPAS', 'R 23'],
            ['X BD 1', 'Selasa', 8, 10, 'Laili Ermawati, S.Pd', 'Matematika', 'R 23'],
            ['X BD 1', 'Rabu', 1, 2, 'Siti Munawaroh, S.Kom.,M.Pd', 'Koding dan Kecerdasan Artifisial', 'Lab. PM Belakang'],
            ['X BD 1', 'Rabu', 3, 4, 'Muto\'atul Khosi\'ah, S.Pd', 'Bahasa Inggris', 'R 23'],
            ['X BD 1', 'Rabu', 5, 7, 'Anisa Kusumawati, S.Pd', 'Dasar PM', 'R 23'],
            ['X BD 1', 'Rabu', 8, 10, 'Angga Widhy Wirawan, S.Pd.,M.Pd', 'Seni Budaya', 'R 23'],
            ['X BD 1', 'Kamis', 1, 3, 'Indriati, S.Pd', 'IPAS', 'R 23'],
            ['X BD 1', 'Kamis', 4, 6, 'Agus Fahruddy, S.Pd., M.Pd', 'PJOK', 'Lapangan'],
            ['X BD 1', 'Kamis', 7, 8, 'Muto\'atul Khosi\'ah, S.Pd', 'Bahasa Inggris', 'R 23'],
            ['X BD 1', 'Kamis', 9, 10, 'Anisa Kusumawati, S.Pd', 'Pendidikan Pancasila', 'R 23'],
            ['X BD 1', 'Jumat', 2, 5, 'Eko Saputro, S.Pd', 'Informatika', 'Lab. PM Belakang'],
            ['X BD 1', 'Jumat', 6, 7, 'Laili Ermawati, S.Pd', 'Matematika', 'R 23'],
            ['X BD 1', 'Jumat', 8, 10, 'Retno Widyastuti, S.Pd., M.Pd', 'Dasar PM', 'R 23'],
            ['X BD 1', 'Jumat', 11, 13, 'Rizki Putri Wulandari, S.Pd', 'Bahasa Jawa', 'R 23'],

            // Page 8: X BD 2
            ['X BD 2', 'Senin', 2, 4, 'Indriati, S.Pd', 'IPAS', 'R 24'],
            ['X BD 2', 'Senin', 5, 8, 'Retno Widyastuti, S.Pd., M.Pd', 'Dasar PM', 'R 24'],
            ['X BD 2', 'Senin', 9, 10, 'Anisa Kusumawati, S.Pd', 'Pendidikan Pancasila', 'R 24'],
            ['X BD 2', 'Selasa', 1, 2, 'Erna Qoriah, S.E.', 'Bahasa Jawa', 'R 24'],
            ['X BD 2', 'Selasa', 3, 4, 'Siti Munawaroh, S.Kom.,M.Pd', 'Koding dan Kecerdasan Artifisial', 'Lab. PM Belakang'],
            ['X BD 2', 'Selasa', 5, 6, 'Laili Ermawati, S.Pd', 'Matematika', 'R 24'],
            ['X BD 2', 'Selasa', 7, 10, 'Ratih Dian Irawati, SE', 'Dasar PM', 'R 24'],
            ['X BD 2', 'Rabu', 1, 3, 'Sinta Lestari, S.Pd.I', 'Pendidikan Agama Islam dan Budi Pekerti', 'R 24'],
            ['X BD 2', 'Rabu', 4, 6, 'Indriati, S.Pd', 'IPAS', 'R 24'],
            ['X BD 2', 'Rabu', 7, 10, 'Eko Saputro, S.Pd', 'Informatika', 'Lab. PM Belakang'],
            ['X BD 2', 'Kamis', 1, 3, 'Anisa Kusumawati, S.Pd', 'Dasar PM', 'R 24'],
            ['X BD 2', 'Kamis', 4, 5, 'Dwi Rini Manfaati, S.Pd', 'Bahasa Inggris', 'R 24'],
            ['X BD 2', 'Kamis', 6, 7, 'Pipit Ambarwati, S.Pd', 'Sejarah', 'R 24'],
            ['X BD 2', 'Kamis', 8, 10, 'Arif Setyobudi, S.Pd', 'Bahasa Indonesia', 'R 24'],
            ['X BD 2', 'Jumat', 2, 2, 'Dian Mawarti, S.Pd', 'BK', 'R 24'],
            ['X BD 2', 'Jumat', 3, 4, 'Dwi Rini Manfaati, S.Pd', 'Bahasa Inggris', 'R 24'],
            ['X BD 2', 'Jumat', 5, 7, 'Agus Fahruddy, S.Pd., M.Pd', 'PJOK', 'Lapangan'],
            ['X BD 2', 'Jumat', 8, 9, 'Arif Setyobudi, S.Pd', 'Bahasa Indonesia', 'R 24'],
            ['X BD 2', 'Jumat', 10, 11, 'Angga Widhy Wirawan, S.Pd.,M.Pd', 'Seni Budaya', 'R 24'],
            ['X BD 2', 'Jumat', 12, 13, 'Laili Ermawati, S.Pd', 'Matematika', 'R 24'],
        ];

        $inserted = 0;
        foreach ($rawJadwal as $r) {
            $namaKelas = $r[0];
            $hari      = $r[1];
            $jamMulai  = $r[2];
            $jamSelesai= $r[3];
            $namaGuru  = $r[4];
            $namaMapel = $r[5];
            $namaRuang = $r[6];

            $idKelas = $kelasMap[$namaKelas] ?? null;
            $targetGuru = $guruAlias[$namaGuru] ?? $namaGuru;
            $idGuru  = $guruMap[$targetGuru] ?? null;
            $kodeMapel = $mapelMap[$namaMapel] ?? null;
            $idRuangan = $ruanganMap[$namaRuang] ?? null;

            if ($idKelas && $idGuru && $kodeMapel && $idRuangan) {
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
            } else {
                echo "FAILED insert for: {$namaKelas} | {$hari} | {$namaGuru} ({$idGuru}) | {$namaMapel} ({$kodeMapel}) | {$namaRuang} ({$idRuangan})\n";
            }
        }

        echo "SUCCESSFULLY inserted {$inserted} schedule items.\n";
    }
}
