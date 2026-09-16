<?php

namespace App\Services;

use App\Models\IzinGuru;
use App\Models\DispenSiswa;
use App\Models\IzinSiswa;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\JurnalMengajar;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Dapatkan Base URL aplikasi secara dinamis dari HTTP Request aktif atau .env.
     *
     * @return string
     */
    public static function getBaseUrl(): string
    {
        try {
            if (request() && request()->getHttpHost()) {
                return request()->getSchemeAndHttpHost();
            }
        } catch (\Throwable $e) {
            // Ignore jika dipanggil dari CLI/queue tanpa HTTP request
        }

        return config('app.url', 'http://localhost:8000');
    }

    /**
     * Kirim notifikasi WA ke Waka Kurikulum & Kepala Sekolah saat ada pengajuan Izin Guru baru.
     *
     * @param IzinGuru $izin
     * @return void
     */
    public static function sendIzinGuruNotificationToWaka(IzinGuru $izin): void
    {
        try {
            $izin->loadMissing('guru');
            $namaGuru = $izin->guru->nama_guru ?? 'Guru';
            $tglMulai = date('d-m-Y', strtotime($izin->tanggal_mulai));
            $tglSelesai = date('d-m-Y', strtotime($izin->tanggal_selesai));
            $alasan = $izin->alasan ?? '-';
            $keterangan = $izin->keterangan ?? '-';

            $appUrl = self::getBaseUrl();

            // 1. Kirim Notifikasi ke Waka Kurikulum & SDM
            $wakaRecipients = User::where('role', 'wakasis_guru')
                ->with('guru')
                ->where('is_active', true)
                ->get();

            $wakaNumbers = [];
            foreach ($wakaRecipients as $r) {
                if ($r->guru && !empty($r->guru->no_hp)) {
                    $fn = self::formatPhoneNumber($r->guru->no_hp);
                    if ($fn) $wakaNumbers[] = $fn;
                }
            }

            if (!empty($wakaNumbers)) {
                $linkWaka = rtrim($appUrl, '/') . '/wakasis-guru/dashboard';
                $msgWaka = "[Izin Guru]\n\n" .
                    "Ada pengajuan izin guru baru yang memerlukan persetujuan Waka Kurikulum & SDM:\n\n" .
                    "📌 *Nama Guru* : {$namaGuru}\n" .
                    "📅 *Tanggal*   : {$tglMulai} s/d {$tglSelesai}\n" .
                    "📝 *Alasan*    : {$alasan}\n" .
                    "ℹ️ *Keterangan*: {$keterangan}\n\n" .
                    "Silakan buka tautan berikut untuk memproses persetujuan:\n" .
                    "🔗 {$linkWaka}\n\n" .
                    "Terima Kasih.";
                self::sendBulkMessage($wakaNumbers, $msgWaka);
            }

            // 2. Kirim Notifikasi ke Kepala Sekolah (dengan Link Dashboard Kepsek)
            $kepsekRecipients = User::where('role', 'kepala_sekolah')
                ->with('guru')
                ->where('is_active', true)
                ->get();

            $kepsekNumbers = [];
            foreach ($kepsekRecipients as $r) {
                if ($r->guru && !empty($r->guru->no_hp)) {
                    $fn = self::formatPhoneNumber($r->guru->no_hp);
                    if ($fn) $kepsekNumbers[] = $fn;
                }
            }

            if (!empty($kepsekNumbers)) {
                $linkKepsek = rtrim($appUrl, '/') . '/kepsek/dashboard';
                $msgKepsek = "[Izin Guru]\n\n" .
                    "Ada pengajuan izin guru baru dari {$namaGuru}:\n\n" .
                    "📌 *Nama Guru* : {$namaGuru}\n" .
                    "📅 *Tanggal*   : {$tglMulai} s/d {$tglSelesai}\n" .
                    "📝 *Alasan*    : {$alasan}\n" .
                    "ℹ️ *Keterangan*: {$keterangan}\n\n" .
                    "Silakan buka tautan berikut untuk memantau & memberi persetujuan:\n" .
                    "🔗 {$linkKepsek}\n\n" .
                    "Terima Kasih.";
                self::sendBulkMessage($kepsekNumbers, $msgKepsek);
            }

        } catch (\Throwable $e) {
            Log::error("WhatsAppService Error sendIzinGuruNotificationToWaka (ID {$izin->id}): " . $e->getMessage());
        }
    }

    /**
     * Kirim notifikasi WA ke Kepala Sekolah saat izin guru telah disetujui Waka Kurikulum & SDM.
     *
     * @param IzinGuru $izin
     * @return void
     */
    public static function sendIzinGuruNotificationToKepsek(IzinGuru $izin): void
    {
        try {
            $izin->loadMissing('guru');
            $namaGuru = $izin->guru->nama_guru ?? 'Guru';
            $tglMulai = date('d-m-Y', strtotime($izin->tanggal_mulai));
            $tglSelesai = date('d-m-Y', strtotime($izin->tanggal_selesai));
            $alasan = $izin->alasan ?? '-';
            $keterangan = $izin->keterangan ?? '-';

            $appUrl = self::getBaseUrl();
            $linkApproval = rtrim($appUrl, '/') . '/kepsek/dashboard';

            $message = "[Izin Guru - Pengesahan Kepsek]\n\n" .
                "Pengajuan izin guru telah disetujui Waka Kurikulum & SDM. Memerlukan pengesahan final Kepala Sekolah:\n\n" .
                "📌 *Nama Guru* : {$namaGuru}\n" .
                "📅 *Tanggal*   : {$tglMulai} s/d {$tglSelesai}\n" .
                "📝 *Alasan*    : {$alasan}\n" .
                "ℹ️ *Keterangan*: {$keterangan}\n\n" .
                "Silakan buka tautan berikut untuk memberikan pengesahan final:\n" .
                "🔗 {$linkApproval}\n\n" .
                "Terima Kasih.";

            // Ambil akun Kepala Sekolah
            $recipients = User::where('role', 'kepala_sekolah')
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
            Log::error("WhatsAppService Error sendIzinGuruNotificationToKepsek (ID {$izin->id}): " . $e->getMessage());
        }
    }

    /**
     * Backward compatibility helper for Izin Guru notification.
     */
    public static function sendIzinGuruNotification(IzinGuru $izin): void
    {
        self::sendIzinGuruNotificationToWaka($izin);
    }

    /**
     * Kirim notifikasi WA saat ada pengajuan Dispen Siswa oleh Guru Piket.
     * Dapat menerima single object DispenSiswa, array of DispenSiswa, atau Collection.
     *
     * @param DispenSiswa|array|\Illuminate\Support\Collection $dispenInput
     * @return void
     */
    public static function sendDispenSiswaNotification($dispenInput): void
    {
        try {
            if ($dispenInput instanceof DispenSiswa) {
                $dispens = collect([$dispenInput]);
            } elseif (is_iterable($dispenInput)) {
                $dispens = collect($dispenInput);
            } else {
                return;
            }

            if ($dispens->isEmpty()) {
                return;
            }

            $firstDispen = $dispens->first();
            $firstDispen->loadMissing('siswa.kelas');
            $tanggal = date('d-m-Y', strtotime($firstDispen->tanggal));
            $keperluan = $firstDispen->keperluan ?? '-';
            $jamMulai = $firstDispen->jam_keluar_rencana ? substr($firstDispen->jam_keluar_rencana, 0, 5) : '-';
            $jamKembali = $firstDispen->jam_kembali_rencana ? substr($firstDispen->jam_kembali_rencana, 0, 5) : 'Selesai KBM';
            
            $isAutoApproved = ($firstDispen->status === 'Disetujui');
            $statusLabel = $isAutoApproved ? 'Disetujui Otomatis (Keperluan Sekolah/Lomba)' : 'Menunggu Persetujuan Waka';

            $daftarSiswaText = "";
            foreach ($dispens as $idx => $d) {
                $d->loadMissing('siswa.kelas');
                $nama = $d->siswa->nama_siswa ?? 'Siswa';
                $kelas = $d->siswa->kelas->nama_kelas ?? '-';
                $num = $idx + 1;
                $daftarSiswaText .= "  {$num}. *{$nama}* ({$kelas})\n";
            }

            $appUrl = self::getBaseUrl();
            $linkDashboard = rtrim($appUrl, '/') . '/wakasis-siswa/dashboard';

            $message = "[Pemberitahuan Dispen Siswa]\n\n" .
                "Terdaftar pengajuan dispensasi siswa dari Guru Piket:\n\n" .
                "📝 *Keperluan* : {$keperluan}\n" .
                "📅 *Tanggal*   : {$tanggal}\n" .
                "⏰ *Waktu*     : {$jamMulai} - {$jamKembali}\n" .
                "📌 *Status*    : *{$statusLabel}*\n\n" .
                "🎓 *Daftar Siswa* (" . count($dispens) . " siswa):\n" .
                $daftarSiswaText . "\n" .
                "Silakan buka tautan berikut untuk memantau data dispensasi:\n" .
                "🔗 {$linkDashboard}\n\n" .
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
                Log::info("WhatsAppService: Tidak ada nomor HP penerima (Wakasis Siswa) yang valid untuk DispenSiswa.");
                return;
            }

            self::sendBulkMessage($phoneNumbers, $message);
        } catch (\Throwable $e) {
            Log::error("WhatsAppService Error (DispenSiswa Notification): " . $e->getMessage());
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

            $appUrl = self::getBaseUrl();
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
            $response = Http::retry(3, 300)
                ->withOptions(['force_ip_resolve' => 'v4'])
                ->withHeaders([
                    'Authorization' => $token,
                ])
                ->timeout(10)
                ->post('https://api.fonnte.com/send', [
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

    /**
     * Kirim notifikasi WA ke Ortu/Wali & Wali Kelas saat siswa tercatat Alpa pada presensi jurnal.
     *
     * @param array|string $nisInput Single NIS or Array of NIS
     * @param JurnalMengajar|int $jurnalInput JurnalMengajar instance or ID Jurnal
     * @return void
     */
    public static function sendAlphaSiswaNotification($nisInput, $jurnalInput): void
    {
        try {
            $nisList = is_array($nisInput) ? $nisInput : [$nisInput];
            if (empty($nisList)) {
                return;
            }

            // Load Jurnal dengan relasi pendukung
            $jurnal = $jurnalInput instanceof JurnalMengajar 
                ? $jurnalInput 
                : JurnalMengajar::find($jurnalInput);

            if (!$jurnal) {
                return;
            }

            $jurnal->loadMissing(['jadwal.kelas', 'jadwal.mapel', 'jadwal.guru']);

            $tanggal = date('d-m-Y', strtotime($jurnal->tanggal));
            $mapel = $jurnal->jadwal->mapel->nama_mapel ?? 'Mata Pelajaran';
            $guruPengajar = $jurnal->jadwal->guru->nama_guru ?? 'Guru Pengajar';
            $jamMulai = $jurnal->jadwal->jam_mulai ?? null;
            $jamSelesai = $jurnal->jadwal->jam_selesai ?? null;
            $jamInfo = ($jamMulai && $jamSelesai) ? "(Jam ke-{$jamMulai} s/d {$jamSelesai})" : "";

            // Pengelompokan data siswa Alpa per Wali Kelas
            $waliKelasMap = []; // [ id_guru => [ 'guru' => Guru, 'kelas' => Kelas, 'siswa_list' => [] ] ]

            foreach ($nisList as $nis) {
                $siswa = Siswa::with('kelas')->find($nis);
                if (!$siswa) continue;

                $namaSiswa = $siswa->nama_siswa;
                $namaKelas = $siswa->kelas->nama_kelas ?? '-';

                // 1. Kirim WA ke Orang Tua / Wali Siswa jika ada nomor HP Wali
                if (!empty($siswa->no_hp_wali)) {
                    $formattedOrtu = self::formatPhoneNumber($siswa->no_hp_wali);
                    if ($formattedOrtu) {
                        $msgOrtu = "[Pemberitahuan Presensi Siswa]\n\n" .
                            "Yth. Orang Tua / Wali dari *{$namaSiswa}*,\n\n" .
                            "Informasi ketidakhadiran siswa di sekolah:\n\n" .
                            "🎓 *Nama Siswa* : {$namaSiswa}\n" .
                            "🏫 *Kelas*      : {$namaKelas}\n" .
                            "📅 *Tanggal*    : {$tanggal}\n" .
                            "📚 *Mata Pelajaran*: {$mapel} {$jamInfo}\n" .
                            "👨‍🏫 *Guru Pengajar*: {$guruPengajar}\n" .
                            "⚠️ *Keterangan* : *ALPA (Tanpa Keterangan)*\n\n" .
                            "Mohon konfirmasi atau hubungi Wali Kelas jika siswa berhalangan hadir.\n\n" .
                            "Terima Kasih.\nSMK Negeri 1";

                        self::sendBulkMessage([$formattedOrtu], $msgOrtu);
                    }
                }

                // 2. Kelompokkan data untuk Notifikasi Wali Kelas
                if ($siswa->kelas && !empty($siswa->kelas->wali_kelas)) {
                    $waliVal = $siswa->kelas->wali_kelas;
                    $waliGuru = Guru::where('nip', $waliVal)
                        ->orWhere('id_guru', $waliVal)
                        ->orWhere('nama_guru', $waliVal)
                        ->first();

                    if ($waliGuru) {
                        $key = $waliGuru->id_guru;
                        if (!isset($waliKelasMap[$key])) {
                            $waliKelasMap[$key] = [
                                'guru'       => $waliGuru,
                                'kelas'      => $siswa->kelas,
                                'siswa_list' => [],
                            ];
                        }
                        $waliKelasMap[$key]['siswa_list'][] = $namaSiswa;

                        // Buat notifikasi dalam aplikasi jika Wali Kelas memiliki akun user
                        if ($waliGuru->user) {
                            Notifikasi::create([
                                'untuk_user_id' => $waliGuru->user->id,
                                'judul'         => "Siswa Alpa: {$namaSiswa}",
                                'pesan'         => "Siswa {$namaSiswa} ({$namaKelas}) tercatat Alpa pada mata pelajaran {$mapel} tanggal {$tanggal}.",
                                'jenis'         => 'siswa_alpha',
                                'ref_id'        => $jurnal->id_jurnal,
                                'sudah_dibaca'  => 0,
                                'created_at'    => now(),
                            ]);
                        }
                    }
                }
            }

            // 3. Kirim rekap pesan WA ke Wali Kelas
            foreach ($waliKelasMap as $data) {
                $waliGuru = $data['guru'];
                $kelasObj = $data['kelas'];
                $siswaList = $data['siswa_list'];

                if (empty($waliGuru->no_hp)) continue;

                $formattedWali = self::formatPhoneNumber($waliGuru->no_hp);
                if (!$formattedWali) continue;

                $daftarText = "";
                foreach ($siswaList as $idx => $sNama) {
                    $num = $idx + 1;
                    $daftarText .= "  {$num}. *{$sNama}*\n";
                }

                $msgWali = "[Laporan Siswa Alpa - Wali Kelas]\n\n" .
                    "Yth. Bpk/Ibu Wali Kelas *{$kelasObj->nama_kelas}*,\n\n" .
                    "Laporan siswa tercatat *ALPA (Tanpa Keterangan)* pada presensi kelas:\n\n" .
                    "📅 *Tanggal*    : {$tanggal}\n" .
                    "📚 *Mata Pelajaran*: {$mapel} {$jamInfo}\n" .
                    "👨‍🏫 *Guru Pengajar*: {$guruPengajar}\n\n" .
                    "📌 *Daftar Siswa Alpa* (" . count($siswaList) . " siswa):\n" .
                    $daftarText . "\n" .
                    "Notifikasi otomatis ini juga telah dikirimkan ke WhatsApp Orang Tua/Wali siswa yang bersangkutan.\n\n" .
                    "Terima Kasih.";

                self::sendBulkMessage([$formattedWali], $msgWali);
            }

        } catch (\Throwable $e) {
            Log::error("WhatsAppService Error (sendAlphaSiswaNotification): " . $e->getMessage());
        }
    }

    /**
     * Kirim notifikasi peringatan WA ke Guru 5 menit sebelum waktu mengajar habis (jika jurnal belum diisi).
     *
     * @param \App\Models\Jadwal $jadwal
     * @param int $sisaMenit
     * @return void
     */
    public static function sendReminderPengisianJurnal(\App\Models\Jadwal $jadwal, int $sisaMenit = 5): void
    {
        try {
            $jadwal->loadMissing(['guru', 'kelas', 'mapel', 'ruangan']);
            $guru = $jadwal->guru;

            if (!$guru || empty($guru->no_hp)) {
                return;
            }

            $formattedPhone = self::formatPhoneNumber($guru->no_hp);
            if (!$formattedPhone) return;

            $namaGuru = $guru->nama_guru;
            $namaKelas = $jadwal->kelas->nama_kelas ?? '-';
            $namaMapel = $jadwal->mapel->nama_mapel ?? '-';
            $namaRuangan = $jadwal->ruangan->nama_ruangan ?? '-';
            $jamInfo = "Jam ke-{$jadwal->jam_mulai} s/d {$jadwal->jam_selesai}";
            $appUrl = self::getBaseUrl();
            $linkIsiJurnal = rtrim($appUrl, '/') . '/guru/jurnal/create/' . $jadwal->id_jadwal;

            $message = "[⚠️ PERINGATAN PENGISIAN JURNAL MENGAJAR]\n\n" .
                "Yth. Bapak/Ibu *{$namaGuru}*,\n\n" .
                "Waktu sesi mengajar Anda akan segera berakhir dalam *{$sisaMenit} menit lagi* dan jurnal belum terisi:\n\n" .
                "🏫 *Kelas*          : {$namaKelas}\n" .
                "📚 *Mata Pelajaran*: {$namaMapel}\n" .
                "🚪 *Ruangan*       : {$namaRuangan}\n" .
                "⏰ *Jam Mengajar*  : {$jamInfo}\n\n" .
                "Mohon segera melakukan pengisian Jurnal Mengajar & Absensi Siswa.\n\n" .
                "🔗 *Klik untuk isi jurnal sekarang*:\n" .
                "{$linkIsiJurnal}\n\n" .
                "Terima Kasih.\nSMK Negeri 1";

            self::sendBulkMessage([$formattedPhone], $message);
        } catch (\Throwable $e) {
            Log::error("WhatsAppService Error (sendReminderPengisianJurnal ID {$jadwal->id_jadwal}): " . $e->getMessage());
        }
    }
}
