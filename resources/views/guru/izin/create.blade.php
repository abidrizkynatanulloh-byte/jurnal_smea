@extends('layouts.app')

@section('title', 'Form Pengajuan Izin Guru - Jurnal Esemkita')

@section('content')
<div class="space-y-8 max-w-2xl mx-auto">
    <!-- Header -->
    <div>
        <div class="flex items-center space-x-2 mb-1">
            <a href="{{ route('guru.izin.index') }}" class="text-xs font-semibold text-[#405078] hover:underline flex items-center space-x-1">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Riwayat Izin</span>
            </a>
        </div>
        <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Formulir Pengajuan Izin Guru</h1>
        <p class="text-xs text-gray-500 mt-0.5">Pengajuan izin ketidakhadiran akan diverifikasi bertahap oleh Waka Kurikulum, Bagian SDM, dan Kepala Sekolah</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl p-6 shadow-sm">
        <form action="{{ route('guru.izin.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Tanggal Mulai Izin *</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required onchange="filterJadwalOtomatis()"
                        class="block w-full px-3.5 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Tanggal Selesai Izin *</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', date('Y-m-d')) }}" required onchange="filterJadwalOtomatis()"
                        class="block w-full px-3.5 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Alasan Ketidakhadiran *</label>
                <select name="alasan" required
                    class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 cursor-pointer">
                    <option value="Sakit">Sakit (Melampirkan Surat Dokter)</option>
                    <option value="Tugas Dinas Luar">Tugas Dinas Luar / Pelatihan Sekolah</option>
                    <option value="Keperluan Keluarga Mendesak">Keperluan Keluarga Mendesak</option>
                    <option value="Cuti Resmi">Cuti Resmi Tahunan / Melahirkan</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <!-- PILIHAN KELAS OTOMATIS BERDASARKAN JADWAL (Poin 12) -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                        Pilih Kelas / Jadwal Mengajar yang Terdampak (Otomatis)
                    </label>
                    <span class="text-[11px] text-[#405078] font-bold">Pilih Centang di Bawah</span>
                </div>

                @if($jadwalGuru && $jadwalGuru->count() > 0)
                    <div class="p-3.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl space-y-2 max-h-48 overflow-y-auto" id="containerJadwal">
                        @foreach($jadwalGuru as $jg)
                            <label class="flex items-start space-x-2.5 p-2 rounded-lg bg-white border border-gray-100 hover:border-[#405078]/40 transition-colors cursor-pointer jadwal-item" data-hari="{{ $jg->hari }}">
                                <input type="checkbox" value="{{ $jg->hari }}: {{ $jg->kelas ? $jg->kelas->nama_kelas : '-' }} (Jam {{ $jg->jam_mulai }}-{{ $jg->jam_selesai }}, {{ $jg->mapel ? $jg->mapel->nama_mapel : '' }})"
                                    onchange="updateKelasTerdampakInput()" class="mt-0.5 rounded text-[#405078] focus:ring-[#405078]">
                                <div class="text-xs">
                                    <span class="font-bold text-[#1E2538]">{{ $jg->hari }}</span> •
                                    <span class="font-semibold text-gray-700">{{ $jg->kelas ? $jg->kelas->nama_kelas : '-' }}</span>
                                    <span class="text-gray-400">({{ $jg->mapel ? $jg->mapel->nama_mapel : '-' }}, Jam {{ $jg->jam_mulai }}-{{ $jg->jam_selesai }})</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 italic">Belum ada jadwal mengajar tetap yang terdaftar.</p>
                @endif

                <input type="text" name="kelas_terdampak" id="kelas_terdampak" placeholder="Otomatis terisi saat mencentang jadwal di atas..." value="{{ old('kelas_terdampak') }}"
                    class="block w-full mt-2 px-3.5 py-2 bg-white border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] font-semibold focus:outline-none focus:border-[#405078]">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Keterangan Tambahan / Penugasan Siswa</label>
                <textarea name="keterangan" rows="3" placeholder="Jelaskan secara singkat rincian keperluan atau catatan instruksi tugas untuk siswa selama tidak hadir..."
                    class="block w-full px-3.5 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15">{{ old('keterangan') }}</textarea>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Unggah Bukti Pendukung (Surat Dokter / Foto Bukti) * <span class="text-rose-600 font-extrabold">(WAJIB)</span></label>
                <input type="file" name="bukti_foto" accept="image/*" required
                    class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#405078]/10 file:text-[#405078] hover:file:bg-[#405078]/20 cursor-pointer">
            </div>

            <div class="flex items-center space-x-3 pt-3">
                <button type="submit" class="py-2.5 px-6 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center space-x-1.5 cursor-pointer">
                    <i data-lucide="send" class="w-3.5 h-3.5"></i>
                    <span>Kirim Pengajuan Izin</span>
                </button>
                <a href="{{ route('guru.izin.index') }}" class="px-5 py-2.5 border border-[#D1D9EB] hover:bg-gray-50 text-gray-600 rounded-xl text-xs font-semibold">
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
