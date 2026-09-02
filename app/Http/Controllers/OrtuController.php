<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\JurnalMengajar;
use App\Models\JurnalDetailKetidakhadiran;
use App\Models\DispenSiswa;
use Carbon\Carbon;

class OrtuController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil data siswa yang terhubung dengan akun orang tua ini
        $siswa = null;
        if ($user && $user->nisn_siswa) {
            $siswa = Siswa::with('kelas')->where('nisn', $user->nisn_siswa)->first();
        }

        // Fallback untuk testing jika relasi nisn_siswa belum di-set pada akun login
        if (!$siswa) {
            $siswa = Siswa::with('kelas')->first();
        }

        $hariIni = Carbon::today()->toDateString();
        $hariMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        $namaHari = $hariMap[Carbon::now()->format('l')] ?? 'Senin';

        $presensiPerJp = collect();
        $dispenHariIni = null;
        $rekapBulanIni = [
            'sakit' => 0,
            'izin'  => 0,
            'alpa'  => 0,
        ];

        if ($siswa && $siswa->id_kelas) {
            // 1. Jadwal kelas anak hari ini
            $jadwalHariIni = Jadwal::with(['mapel', 'guru', 'ruangan'])
                ->where('id_kelas', $siswa->id_kelas)
                ->where('hari', $namaHari)
                ->orderBy('jam_mulai')
                ->get();

            // 2. Cek status presensi per jam pelajaran
            foreach ($jadwalHariIni as $j) {
                $jurnal = JurnalMengajar::where('id_jadwal', $j->id_jadwal)
                    ->where('tanggal', $hariIni)
                    ->first();

                $statusKehadiran = 'Belum Dimulai';
                $badgeClass = 'bg-gray-100 text-gray-500';

                if ($jurnal) {
                    $tidakHadir = JurnalDetailKetidakhadiran::where('id_jurnal', $jurnal->id_jurnal)
                        ->where('id_siswa', $siswa->nis)
                        ->first();

                    if ($tidakHadir) {
                        $statusKehadiran = $tidakHadir->keterangan; // Sakit, Izin, atau Alpa
                        $badgeClass = match ($tidakHadir->keterangan) {
                            'Sakit' => 'bg-blue-50 text-blue-700',
                            'Izin'  => 'bg-amber-50 text-amber-700',
                            default => 'bg-rose-50 text-rose-700',
                        };
                    } else {
                        $statusKehadiran = 'Hadir';
                        $badgeClass = 'bg-emerald-50 text-emerald-700';
                    }
                }

                $presensiPerJp->push([
                    'jam_ke'   => "Jam {$j->jam_mulai}-{$j->jam_selesai}",
                    'mapel'    => $j->mapel ? $j->mapel->nama_mapel : 'Pelajaran',
                    'guru'     => $j->guru ? $j->guru->nama_guru : '-',
                    'ruangan'  => $j->ruangan ? $j->ruangan->nama_ruangan : '-',
                    'status'   => $statusKehadiran,
                    'badge'    => $badgeClass,
                ]);
            }

            // 3. Status izin / dispensasi anak hari ini
            $dispenHariIni = DispenSiswa::where('nis', $siswa->nis)
                ->where('tanggal', $hariIni)
                ->latest()
                ->first();

            // 4. Rekap ketidakhadiran bulan ini
            $awalBulan = Carbon::now()->startOfMonth()->toDateString();
            $akhirBulan = Carbon::now()->endOfMonth()->toDateString();

            $ketidakhadiranList = JurnalDetailKetidakhadiran::where('id_siswa', $siswa->nis)
                ->whereHas('jurnal', function ($q) use ($awalBulan, $akhirBulan) {
                    $q->whereBetween('tanggal', [$awalBulan, $akhirBulan]);
                })
                ->get();

            $rekapBulanIni['sakit'] = $ketidakhadiranList->where('keterangan', 'Sakit')->count();
            $rekapBulanIni['izin']  = $ketidakhadiranList->where('keterangan', 'Izin')->count();
            $rekapBulanIni['alpa']  = $ketidakhadiranList->where('keterangan', 'Alpa')->count();
        }

        // 5. Riwayat Ketidakhadiran (Semua Waktu) dikelompokkan per tanggal
        $riwayatAbsen = [];
        if ($siswa) {
            $semuaKetidakhadiran = JurnalDetailKetidakhadiran::with(['jurnal.jadwal.jamMulaiData', 'jurnal.jadwal.jamSelesaiData'])
                ->where('id_siswa', $siswa->nis)
                ->get();
                
            $groupedByDate = [];
            foreach ($semuaKetidakhadiran as $kh) {
                if (!$kh->jurnal) continue;
                $tgl = $kh->jurnal->tanggal;
                $keterangan = $kh->keterangan;
                $jamM = $kh->jurnal->jadwal->jamMulaiData->jam_ke ?? '?';
                $jamS = $kh->jurnal->jadwal->jamSelesaiData->jam_ke ?? '?';
                
                $teksJam = $jamM == $jamS ? "Jam ke-$jamM" : "Jam ke-$jamM-$jamS";
                
                if (!isset($groupedByDate[$tgl])) {
                    $groupedByDate[$tgl] = [];
                }
                if (!isset($groupedByDate[$tgl][$keterangan])) {
                    $groupedByDate[$tgl][$keterangan] = [];
                }
                $groupedByDate[$tgl][$keterangan][] = $teksJam;
            }
            
            // Format for view
            foreach ($groupedByDate as $tgl => $ketGroups) {
                foreach ($ketGroups as $ket => $jams) {
                    // Check if they are absent for all periods? Actually, we don't know the exact number of periods for that day easily here.
                    // But if there are many, we can just list them. "Jam ke-1, Jam ke-2, Jam ke-3..."
                    // A simple heuristic: if count > 4, maybe call it "Hampir Full / Full", or just list them.
                    $jamText = implode(', ', $jams);
                    if (count($jams) >= 4) {
                        $jamText = "1 Hari Full (" . count($jams) . " Sesi)";
                    } else {
                        $jamText = "Di " . $jamText;
                    }
                    
                    $riwayatAbsen[] = [
                        'tanggal' => $tgl,
                        'keterangan' => $ket,
                        'detail_jam' => $jamText
                    ];
                }
            }
            // Sort by tanggal desc
            usort($riwayatAbsen, function($a, $b) {
                return strtotime($b['tanggal']) - strtotime($a['tanggal']);
            });
        }

        return view('ortu.dashboard', compact(
            'siswa',
            'hariIni',
            'namaHari',
            'presensiPerJp',
            'dispenHariIni',
            'rekapBulanIni',
            'riwayatAbsen'
        ));
    }
}
