<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WhatsAppService;

class KirimRekapAlphaOrtuCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presensi:kirim-rekap-alpha {tanggal? : Tanggal rekap Y-m-d (opsional, default hari ini)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim rekapitulasi notifikasi WA siswa Alpa (Full vs Jam Tertentu) ke Orang Tua & Wali Kelas setelah jam sekolah selesai';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tanggal = $this->argument('tanggal');
        $this->info("Menjalankan konsolidasi notifikasi rekap Alpa untuk tanggal: " . ($tanggal ?? date('Y-m-d')));

        $res = WhatsAppService::sendDailyAlphaSummaryToParents($tanggal);

        $this->info("Proses selesai.");
        $this->table(
            ['Total Siswa Alpa', 'Alpa Full Day', 'Alpa Jam Tertentu', 'WA Ortu Terkirim', 'WA Wali Terkirim'],
            [[$res['total_siswa_alpa'], $res['full_alpa'], $res['partial_alpa'], $res['ortu_sent'], $res['wali_sent']]]
        );

        return Command::SUCCESS;
    }
}
