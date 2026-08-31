@extends('layouts.app')

@section('title', 'Kelola Siswa - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-dark tracking-tight">Data Siswa</h1>
            <p class="text-sm text-gray-500 mt-1">
                Total terdaftar: <span class="font-semibold text-brand">{{ $totalSiswa }} Siswa</span>
            </p>
        </div>
    </div>

    <!-- Responsive Grid: Form on Left/Top, List on Right/Bottom -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- BAGIAN 1: FORM TAMBAH SISWA BARU -->
        <div class="bg-white border border-[#19140015] rounded-2xl shadow-sm p-6 lg:sticky lg:top-8">
            <h3 class="font-bold text-dark text-base mb-4 flex items-center space-x-2">
                <i data-lucide="user-plus" class="w-5 h-5 text-brand"></i>
                <span>Tambah Siswa Baru</span>
            </h3>
            
            <form action="{{ route('admin.siswa.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="nis" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">NIS (Nomor Induk Siswa)</label>
                    <input type="text" name="nis" id="nis" value="{{ old('nis') }}" placeholder="Contoh: 23241001" required 
                        class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark placeholder-gray-400 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                </div>

                <div>
                    <label for="nisn" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">NISN (Nasional)</label>
                    <input type="text" name="nisn" id="nisn" value="{{ old('nisn') }}" placeholder="Contoh: 0081234567" required
                        class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark placeholder-gray-400 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                </div>

                <div>
                    <label for="nama_siswa" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Nama Lengkap Siswa</label>
                    <input type="text" name="nama_siswa" id="nama_siswa" value="{{ old('nama_siswa') }}" placeholder="Masukkan Nama Lengkap" required
                        class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark placeholder-gray-400 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                </div>

                <div>
                    <label for="id_kelas" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Kelas</label>
                    <select name="id_kelas" id="id_kelas" required
                        class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all cursor-pointer">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="kota_lahir" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Tempat Lahir</label>
                        <input type="text" name="kota_lahir" id="kota_lahir" value="{{ old('kota_lahir') }}" placeholder="Kota"
                            class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark placeholder-gray-400 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                    </div>
                    <div>
                        <label for="tanggal_lahir" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Tgl Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                            class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                    </div>
                </div>

                <div>
                    <label for="alamat" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Alamat Lengkap</label>
                    <textarea name="alamat" id="alamat" rows="2" placeholder="Masukkan Alamat Tinggal Siswa"
                        class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark placeholder-gray-400 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all resize-none">{{ old('alamat') }}</textarea>
                </div>

                <button type="submit" class="w-full mt-2 py-3 px-4 bg-brand hover:bg-brand-hover text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center space-x-2 cursor-pointer">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Siswa</span>
                </button>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR & FILTER SISWA -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- FILTER CARD -->
            <div class="bg-white border border-[#19140015] rounded-2xl shadow-sm p-5">
                <form action="{{ route('admin.siswa.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Cari Siswa</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / NIS / NISN..." 
                                class="block w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark placeholder-gray-400 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                        </div>
                    </div>

                    <div class="flex space-x-2 w-full sm:w-auto">
                        <button type="submit" class="flex-1 sm:flex-initial px-5 py-2 bg-dark hover:bg-dark-hover text-white rounded-xl text-sm font-semibold transition-colors flex items-center justify-center space-x-1.5 cursor-pointer">
                            <i data-lucide="filter" class="w-4 h-4"></i>
                            <span>Cari</span>
                        </button>
                        @if (request('search'))
                            <a href="{{ route('admin.siswa.index') }}" class="px-4 py-2 border border-gray-200 hover:bg-gray-50 text-gray-500 hover:text-dark rounded-xl text-sm font-semibold transition-colors flex items-center justify-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- TABLE CARD -->
            <div class="bg-white border border-[#19140015] rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#19140015]">
                                <th class="py-4 px-6 w-28">NIS</th>
                                <th class="py-4 px-6 w-28">NISN</th>
                                <th class="py-4 px-6">Nama Siswa</th>
                                <th class="py-4 px-6">Kelas</th>
                                <th class="py-4 px-6">TTL</th>
                                <th class="py-4 px-6">Alamat</th>
                                <th class="py-4 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#19140010] text-sm text-gray-600">
                            @forelse ($siswaList as $s)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-6 font-semibold text-dark">{{ $s->nis }}</td>
                                    <td class="py-4 px-6 text-gray-500">{{ $s->nisn }}</td>
                                    <td class="py-4 px-6 font-medium text-dark">{{ $s->nama_siswa }}</td>
                                    <td class="py-4 px-6">
                                        <span class="px-2.5 py-1 bg-gray-100 rounded-md text-xs font-bold text-gray-700">
                                            {{ $s->kelas ? $s->kelas->nama_kelas : '-' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-xs text-gray-500 leading-normal">
                                        @if($s->kota_lahir || $s->tanggal_lahir)
                                            <span class="font-medium text-gray-700">{{ $s->kota_lahir ?? '-' }}</span>, <br>
                                            <span>{{ $s->tanggal_lahir ?? '-' }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-xs text-gray-500 max-w-xs truncate" title="{{ $s->alamat }}">{{ $s->alamat ?? '-' }}</td>
                                    <td class="py-4 px-6 text-center">
                                        <form action="{{ route('admin.siswa.destroy', $s->nis) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus siswa ini?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors cursor-pointer" title="Hapus Siswa">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-gray-400 italic">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                        Tidak ada data siswa yang cocok dengan pencarian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION FOOTER -->
                @if ($siswaList->hasPages())
                    <div class="px-6 py-4 bg-gray-50 border-t border-[#19140015] flex items-center justify-between">
                        <div class="text-xs text-gray-500">
                            Menampilkan halaman <span class="font-semibold text-dark">{{ $siswaList->currentPage() }}</span> dari <span class="font-semibold text-dark">{{ $siswaList->lastPage() }}</span>
                        </div>
                        <div class="inline-flex space-x-1.5">
                            @if ($siswaList->previousPageUrl())
                                <a href="{{ $siswaList->previousPageUrl() }}" class="px-3.5 py-1.5 border border-gray-250 bg-white hover:bg-gray-50 text-gray-600 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1">
                                    <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
                                    <span>Sebelumnya</span>
                                </a>
                            @endif
                            @if ($siswaList->nextPageUrl())
                                <a href="{{ $siswaList->nextPageUrl() }}" class="px-3.5 py-1.5 border border-gray-250 bg-white hover:bg-gray-50 text-gray-600 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1">
                                    <span>Selanjutnya</span>
                                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection