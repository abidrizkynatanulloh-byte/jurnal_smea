@extends('layouts.app')

@section('title', 'Piket Dashboard - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-dark tracking-tight">Dashboard Guru Piket</h1>
            <p class="text-sm text-gray-500 mt-1">
                Selamat datang kembali, <span class="font-semibold text-brand">{{ Auth::user()->username }}</span>
            </p>
        </div>
    </div>

    <!-- Responsive Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- BAGIAN 1: FORM INPUT PENGAJUAN DISPEN SISWA -->
        <div class="bg-white border border-[#19140015] rounded-2xl shadow-sm p-6 lg:sticky lg:top-8">
            <h3 class="font-bold text-dark text-base mb-4 flex items-center space-x-2">
                <i data-lucide="file-plus-2" class="w-5 h-5 text-brand"></i>
                <span>Input Pengajuan Dispensasi</span>
            </h3>
            
            <form action="{{ route('piket.dispen.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="nis" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Pilih Siswa</label>
                    <select name="nis" id="nis" required 
                        class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all cursor-pointer">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($daftarSiswa as $s)
                            <option value="{{ $s->nis }}" {{ old('nis') == $s->nis ? 'selected' : '' }}>
                                {{ $s->nis }} - {{ $s->nama_siswa }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="keperluan" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Alasan / Keperluan Dispensasi</label>
                    <textarea name="keperluan" id="keperluan" rows="3" placeholder="Contoh: Mengikuti perwakilan lomba cerdas cermat di tingkat kecamatan..." required
                        class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark placeholder-gray-400 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all resize-none">{{ old('keperluan') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="jam_keluar_rencana" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Jam Keluar</label>
                        <input type="time" name="jam_keluar_rencana" id="jam_keluar_rencana" required
                            class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all cursor-pointer">
                    </div>
                    <div>
                        <label for="jam_kembali_rencana" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Jam Kembali (Opsional)</label>
                        <input type="time" name="jam_kembali_rencana" id="jam_kembali_rencana"
                            class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all cursor-pointer">
                    </div>
                </div>

                <button type="submit" class="w-full mt-2 py-3 px-4 bg-brand hover:bg-brand-hover text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center space-x-2 cursor-pointer">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Kirim Pengajuan Kesiswaan</span>
                </button>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR DISPEN HARI INI -->
        <div class="lg:col-span-2">
            
            <!-- TABLE CARD -->
            <div class="bg-white border border-[#19140015] rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-[#19140015]">
                    <h3 class="font-bold text-dark text-base flex items-center space-x-2">
                        <i data-lucide="clipboard-list" class="w-5 h-5 text-gray-600"></i>
                        <span>Pengajuan Dispensasi Hari Ini</span>
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#19140015]">
                                <th class="py-4 px-6 text-center w-16">No</th>
                                <th class="py-4 px-6 w-28">NIS</th>
                                <th class="py-4 px-6">Nama Siswa</th>
                                <th class="py-4 px-6 w-48">Jam Keluar/Kembali</th>
                                <th class="py-4 px-6">Alasan Keperluan</th>
                                <th class="py-4 px-6 w-48 text-center">Status Persetujuan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#19140010] text-sm text-gray-600">
                            @forelse ($dispenHariIni as $index => $d)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-6 text-center font-medium text-gray-400">{{ $index + 1 }}</td>
                                    <td class="py-4 px-6 font-semibold text-dark">{{ $d->nis }}</td>
                                    <td class="py-4 px-6 font-bold text-gray-800">{{ $d->siswa ? $d->siswa->nama_siswa : '-' }}</td>
                                    <td class="py-4 px-6 text-xs leading-normal">
                                        <div class="flex items-center space-x-1.5">
                                            <span class="font-semibold text-gray-700">Rencana Keluar:</span>
                                            <span class="text-gray-500">{{ substr($d->jam_keluar_rencana, 0, 5) ?? '-' }}</span>
                                        </div>
                                        <div class="flex items-center space-x-1.5 mt-0.5">
                                            <span class="font-semibold text-gray-700">Rencana Kembali:</span>
                                            <span class="text-gray-500">{{ $d->jam_kembali_rencana ? substr($d->jam_kembali_rencana, 0, 5) : 'Tidak kembali' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-xs text-gray-500 max-w-xs truncate" title="{{ $d->keperluan }}">{{ $d->keperluan }}</td>
                                    <td class="py-4 px-6 text-center">
                                        @if ($d->status === 'Menunggu')
                                            <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-full space-x-1 animate-pulse">
                                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                                <span>Menunggu ACC Wakasis</span>
                                            </span>
                                        @elseif ($d->status === 'Disetujui')
                                            <span class="inline-flex items-center px-2.5 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full space-x-1">
                                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                                <span>Disetujui Wakasis</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 bg-red-50 text-red-750 text-xs font-semibold rounded-full space-x-1">
                                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                                <span>Ditolak Wakasis</span>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-400 italic">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                        Belum ada pengajuan dispensasi untuk hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection