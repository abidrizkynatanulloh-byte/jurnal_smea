@extends('layouts.app')

@section('title', 'Form Pengajuan Izin Guru - Jurnal Esemkita')

@section('content')
<div class="space-y-4 max-w-2xl mx-auto">
    <!-- Header -->
    <div>
        <div class="flex items-center space-x-1.5 mb-1">
            <a href="{{ route('guru.izin.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center space-x-1">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Riwayat Izin</span>
            </a>
        </div>
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Formulir Pengajuan Izin Guru</h1>
        <p class="text-xs text-slate-500 mt-0.5">Pengajuan izin ketidakhadiran akan diverifikasi bertahap oleh Waka Kurikulum, Bagian SDM, dan Kepala Sekolah</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs">
        <form action="{{ route('guru.izin.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tanggal Mulai Izin *</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required onchange="filterJadwalOtomatis()"
                        class="block w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-[#1E2538]">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Tanggal Selesai Izin *</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', date('Y-m-d')) }}" required onchange="filterJadwalOtomatis()"
                        class="block w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-[#1E2538]">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Alasan Ketidakhadiran *</label>
                <select name="alasan" required
                    class="block w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-[#1E2538] cursor-pointer">
                    <option value="Sakit">Sakit (Melampirkan Surat Dokter)</option>
                    <option value="Tugas Dinas Luar">Tugas Dinas Luar / Pelatihan Sekolah</option>
                    <option value="Keperluan Keluarga Mendesak">Keperluan Keluarga Mendesak</option>
                    <option value="Cuti Resmi">Cuti Resmi Tahunan / Melahirkan</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <!-- PILIHAN KELAS OTOMATIS BERDASARKAN JADWAL (Poin 12) -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        Pilih Kelas / Jadwal Terdampak (Otomatis)
                    </label>
                    <span class="text-[10px] text-slate-500 font-semibold">Pilih Centang di Bawah</span>
                </div>

                @if($jadwalGuru && $jadwalGuru->count() > 0)
                    <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-lg space-y-1.5 max-h-40 overflow-y-auto" id="containerJadwal">
                        @foreach($jadwalGuru as $jg)
                            <label class="flex items-start space-x-2 p-1.5 rounded-md bg-white border border-slate-200/80 hover:border-slate-400 transition-colors cursor-pointer jadwal-item" data-hari="{{ $jg->hari }}">
                                <input type="checkbox" value="{{ $jg->hari }}: {{ $jg->kelas ? $jg->kelas->nama_kelas : '-' }} (Jam {{ $jg->jam_mulai }}-{{ $jg->jam_selesai }}, {{ $jg->mapel ? $jg->mapel->nama_mapel : '' }})"
                                    onchange="updateKelasTerdampakInput()" class="mt-0.5 rounded text-[#1E2538] focus:ring-[#1E2538]">
                                <div class="text-xs">
                                    <span class="font-bold text-slate-800">{{ $jg->hari }}</span> •
                                    <span class="font-semibold text-slate-700">{{ $jg->kelas ? $jg->kelas->nama_kelas : '-' }}</span>
                                    <span class="text-slate-400">({{ $jg->mapel ? $jg->mapel->nama_mapel : '-' }}, Jam {{ $jg->jam_mulai }}-{{ $jg->jam_selesai }})</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">Belum ada jadwal mengajar tetap yang terdaftar.</p>
                @endif

                <input type="text" name="kelas_terdampak" id="kelas_terdampak" placeholder="Otomatis terisi saat mencentang jadwal di atas..." value="{{ old('kelas_terdampak') }}"
                    class="block w-full mt-1.5 h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 font-medium focus:outline-none focus:border-[#1E2538]">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Keterangan Tambahan / Penugasan Siswa</label>
                <textarea name="keterangan" rows="2" placeholder="Jelaskan secara singkat rincian keperluan atau catatan instruksi tugas untuk siswa selama tidak hadir..."
                    class="block w-full px-2.5 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#1E2538]">{{ old('keterangan') }}</textarea>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Unggah Bukti Pendukung (Surat Dokter / Foto Bukti) * <span class="text-rose-600 font-extrabold">(WAJIB)</span></label>
                <input type="file" name="bukti_foto" accept="image/*" required
                    class="block w-full text-xs text-slate-500 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
            </div>

            <div class="flex items-center space-x-2 pt-2">
                <button type="submit" class="h-8.5 px-4 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg text-xs font-semibold transition-colors shadow-2xs flex items-center space-x-1.5 cursor-pointer">
                    <i data-lucide="send" class="w-3.5 h-3.5"></i>
                    <span>Kirim Pengajuan Izin</span>
                </button>
                <a href="{{ route('guru.izin.index') }}" class="h-8.5 px-3 border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-lg text-xs font-semibold flex items-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    function filterJadwalOtomatis() {
        const tglMulaiVal = document.getElementById('tanggal_mulai').value;
        const tglSelesaiVal = document.getElementById('tanggal_selesai').value;

        if (!tglMulaiVal) return;

        const start = new Date(tglMulaiVal);
        const end = tglSelesaiVal ? new Date(tglSelesaiVal) : new Date(tglMulaiVal);

        // Ambil semua nama hari dalam rentang tanggal
        const activeDays = new Set();
        let cur = new Date(start);
        while (cur <= end) {
            activeDays.add(dayNames[cur.getDay()]);
            cur.setDate(cur.getDate() + 1);
        }

        const items = document.querySelectorAll('.jadwal-item');
        items.forEach(item => {
            const hari = item.getAttribute('data-hari');
            const checkbox = item.querySelector('input[type="checkbox"]');
            if (activeDays.has(hari)) {
                item.style.display = 'flex';
                checkbox.checked = true; // Otomatis centang jadwal pada hari tersebut
            } else {
                item.style.display = 'none';
                checkbox.checked = false;
            }
        });

        updateKelasTerdampakInput();
    }

    function updateKelasTerdampakInput() {
        const checked = [];
        document.querySelectorAll('.jadwal-item input[type="checkbox"]:checked').forEach(cb => {
            checked.push(cb.value);
        });
        document.getElementById('kelas_terdampak').value = checked.join('; ');
    }

    // Jalankan saat pertama kali dibuka
    document.addEventListener('DOMContentLoaded', function() {
        filterJadwalOtomatis();
    });
</script>
@endsection
