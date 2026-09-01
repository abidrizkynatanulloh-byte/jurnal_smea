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
        <p class="text-xs text-gray-500 mt-0.5">Pengajuan izin ketidakhadiran akan diverifikasi bertahap oleh Waka Kurikulum, Guru Piket, dan Kepala Sekolah</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl p-6 shadow-sm">
        <form action="{{ route('guru.izin.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Tanggal Mulai Izin *</label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required
                        class="block w-full px-3.5 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Tanggal Selesai Izin *</label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', date('Y-m-d')) }}" required
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

            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Kelas / Jam Mengajar yang Terdampak</label>
                <input type="text" name="kelas_terdampak" placeholder="Contoh: Kelas X RPL 1 (Jam 1-4), XI AKL 2 (Jam 5-6)" value="{{ old('kelas_terdampak') }}"
                    class="block w-full px-3.5 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Keterangan Tambahan</label>
                <textarea name="keterangan" rows="3" placeholder="Jelaskan secara singkat rincian keperluan atau catatan tugas untuk siswa..."
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
@endsection
