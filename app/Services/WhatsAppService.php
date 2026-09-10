<?php

namespace App\Services;

use App\Models\IzinGuru;
use App\Models\DispenSiswa;
use App\Models\IzinSiswa;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Kirim notifikasi WA saat ada pengajuan Izin Guru baru.
     *
     * @param IzinGuru $izin
     * @return void
     */
    public static function sendIzinGuruNotification(IzinGuru $izin): void
    {
        try {
            $izin->loadMissing('guru');
            $namaGuru = $izin->guru->nama_guru ?? 'Guru';
            $tglMulai = date('d-m-Y', strtotime($izin->tanggal_mulai));
            $tglSelesai = date('d-m-Y', strtotime($izin->tanggal_selesai));
            $alasan = $izin->alasan ?? '-';
            $keterangan = $izin->keterangan ?? '-';

            $appUrl = config('app.url', 'http://localhost:8000');
            $linkApproval = rtrim($appUrl, '/') . '/wakasis-guru/dashboard';

            $message = "[Izin Guru]\n\n" .
                "Ada pengajuan izin guru baru yang memerlukan persetujuan:\n\n" .
                "📌 *Nama Guru* : {$namaGuru}\n" .
                "📅 *Tanggal*   : {$tglMulai} s/d {$tglSelesai}\n" .
                "📝 *Alasan*    : {$alasan}\n" .
                "ℹ️ *Keterangan*: {$keterangan}\n\n" .
                "Silakan buka tautan berikut untuk memproses persetujuan:\n" .
                "🔗 {$linkApproval}\n\n" .
                "Terima Kasih.";

            // Ambil akun Waka Guru dan Kepsek
            $recipients = User::whereIn('role', ['wakasis_guru', 'kepala_sekolah'])
                ->with('guru')
                ->where('is_active', true)
                ->get();

            $phoneNumbers = [];
            foreach ($recipients as $recipient) {
                if ($recipient->guru && !empty($recipient->guru->no_hp)) {
                    $formattedNo = self::formatPhoneNumber($recipient->guru->no_hp);
                    if ($formattedNo) {
                        $phoneNumbers[] = $formattedNo;
                    }
                }
            }

            if (empty($phoneNumbers)) {
                Log::info("WhatsAppService: Tidak ada nomor HP penerima (Waka Guru / Kepsek) yang valid untuk notifikasi IzinGuru ID {$izin->id}.");
                return;
            }

            self::sendBulkMessage($phoneNumbers, $message);
        } catch (\Throwable $e) {
            Log::error("WhatsAppService Error (IzinGuru ID {$izin->id}): " . $e->getMessage());
        }
    }

    /**
     * Kirim notifikasi WA saat ada pengajuan Dispen Siswa oleh Guru Piket.
     *
     * @param DispenSiswa $dispen
     * @return void
     */
    public static function sendDispenSiswaNotification(DispenSiswa $dispen): void
    {
        try {
            $dispen->loadMissing('siswa.kelas');
            $namaSiswa = $dispen->siswa->nama_siswa ?? 'Siswa';
            $kelas = $dispen->siswa->kelas->nama_kelas ?? '-';
            $tanggal = date('d-m-Y', strtotime($dispen->tanggal));
            $keperluan = $dispen->keperluan ?? '-';
            $jamMulai = $dispen->jam_keluar_rencana ?? '-';
            $jamKembali = $dispen->jam_kembali_rencana ?? 'Selesai';

            $appUrl = config('app.url', 'http://localhost:8000');
            $linkApproval = rtrim($appUrl, '/') . '/wakasis-siswa/dashboard';

            $message = "[Dispen Siswa]\n\n" .
                "Ada pengajuan dispensasi siswa baru yang memerlukan persetujuan:\n\n" .
                "🎓 *Nama Siswa* : {$namaSiswa} ({$kelas})\n" .
                "📅 *Tanggal*    : {$tanggal}\n" .
                "⏰ *Waktu*      : {$jamMulai} - {$jamKembali}\n" .
                "📝 *Keperluan*  : {$keperluan}\n\n" .
                "Silakan buka tautan berikut untuk memproses persetujuan:\n" .
                "🔗 {$linkApproval}\n\n" .
                "Terima Kasih.";

            // Ambil akun Waka Kesiswaan Siswa
            $recipients = User::where('role', 'wakasis_siswa')
                ->with('guru')
                ->where('is_active', true)
                ->get();

            $phoneNumbers = [];
            foreach ($recipients as $recipient) {
                if ($recipient->guru && !empty($recipient->guru->no_hp)) {
                    $formattedNo = self::formatPhoneNumber($recipient->guru->no_hp);
                    if ($formattedNo) {
                        $phoneNumbers[] = $formattedNo;
                    }
                }
            }

            if (empty($phoneNumbers)) {
                Log::info("WhatsAppService: Tidak ada nomor HP penerima (Wakasis Siswa) yang valid untuk DispenSiswa ID {$dispen->id}.");
                return;
            }

            self::sendBulkMessage($phoneNumbers, $message);
        } catch (\Throwable $e) {
            Log::error("WhatsAppService Error (DispenSiswa ID {$dispen->id}): " . $e->getMessage());
        }
    }

    /**
     * Kirim notifikasi WA saat ada pengajuan Izin/Sakit Siswa oleh Orang Tua.
     *
     * @param IzinSiswa $izin
     * @return void
     */
    public static function sendIzinSiswaNotification(IzinSiswa $izin): void
    {
        try {
            $izin->loadMissing('siswa.kelas');
            $namaSiswa = $izin->siswa->nama_siswa ?? 'Siswa';
            $kelas = $izin->siswa->kelas->nama_kelas ?? '-';
            $kategori = strtoupper($izin->kategori ?? 'IZIN');
            $tglMulai = date('d-m-Y', strtotime($izin->tanggal_mulai));
            $tglSelesai = date('d-m-Y', strtotime($izin->tanggal_selesai));
            $alasan = $izin->alasan ?? '-';

            $appUrl = config('app.url', 'http://localhost:8000');
            $linkApproval = rtrim($appUrl, '/') . '/piket/dashboard';

            $message = "[Izin Siswa]\n\n" .
                "Ada pengajuan {$kategori} siswa dari Orang Tua:\n\n" .
                "🎓 *Nama Siswa* : {$namaSiswa} ({$kelas})\n" .
                "📌 *Kategori*   : {$kategori}\n" .
                "📅 *Tanggal*    : {$tglMulai} s/d {$tglSelesai}\n" .
                "📝 *Alasan*     : {$alasan}\n\n" .
                "Silakan buka tautan berikut untuk memeriksa di Dashboard Piket:\n" .
                "🔗 {$linkApproval}\n\n" .
                "Terima Kasih.";

            // Ambil akun Guru Piket & Wakasis Siswa
            $recipients = User::whereIn('role', ['guru_piket', 'wakasis_siswa'])
                ->with('guru')
                ->where('is_active', true)
                ->get();

            $phoneNumbers = [];
            foreach ($recipients as $recipient) {
                if ($recipient->guru && !empty($recipient->guru->no_hp)) {
                    $formattedNo = self::formatPhoneNumber($recipient->guru->no_hp);
                    if ($formattedNo) {
                        $phoneNumbers[] = $formattedNo;
                    }
                }
            }

            if (!empty($phoneNumbers)) {
                self::sendBulkMessage($phoneNumbers, $message);
            }
        } catch (\Throwable $e) {
            Log::error("WhatsAppService Error (IzinSiswa ID {$izin->id}): " . $e->getMessage());
        }
    }

    /**
     * Kirim pesan WA ke banyak nomor via Fonnte atau Gateway pilihan.
     *
     * @param array $targetNumbers
     * @param string $message
     * @return bool
     */
    public static function sendBulkMessage(array $targetNumbers, string $message): bool
    {
        $token = config('services.whatsapp.fonnte_token') ?? env('FONNTE_TOKEN');

        if (empty($token)) {
            Log::info("WhatsAppService: FONNTE_TOKEN belum dikonfigurasi di .env. Pesan tidak dikirim ke API, tetapi tercatat di log.", [
                'targets' => $targetNumbers,
                'message' => $message,
            ]);
            return false;
        }

        $targetsStr = implode(',', array_unique($targetNumbers));

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->timeout(10)->post('https://api.fonnte.com/send', [
                'target' => $targetsStr,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                Log::info("WhatsAppService: Pesan WA berhasil dikirim ke {$targetsStr}.", $response->json() ?? []);
                return true;
            }

            Log::warning("WhatsAppService: Gagal mengirim pesan WA ke {$targetsStr}. Status: {$response->status()}", [
                'response' => $response->body(),
            ]);
            return false;
        } catch (\Throwable $e) {
            Log::error("WhatsAppService HTTP Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Format nomor HP ke standar internasional Indonesia (628...).
     *
     * @param string $phone
     * @return string|null
     */
    public static function formatPhoneNumber(string $phone): ?string
    {
        // Clean non-digit characters
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        if (empty($cleaned)) {
            return null;
        }

        if (str_starts_with($cleaned, '08')) {
            return '62' . substr($cleaned, 1);
        }

        if (str_starts_with($cleaned, '8')) {
            return '62' . $cleaned;
        }

        if (str_starts_with($cleaned, '628')) {
            return $cleaned;
        }

        return $cleaned;
    }
}
