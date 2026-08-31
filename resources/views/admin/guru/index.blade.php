@extends('layouts.app')

@section('title', 'Kelola Guru & Pegawai - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-dark tracking-tight">Data Guru & Pegawai</h1>
            <p class="text-sm text-gray-500 mt-1">
                Total terdaftar: <span class="font-semibold text-brand">{{ $totalGuru }} Pegawai</span>
            </p>
        </div>
    </div>

    <!-- Responsive Grid: Form on Left/Top, List on Right/Bottom -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- BAGIAN 1: FORM TAMBAH PEGAWAI BARU (1 Column) -->
        <div class="bg-white border border-[#19140015] rounded-2xl shadow-sm p-6 lg:sticky lg:top-8">
            <h3 class="font-bold text-dark text-base mb-4 flex items-center space-x-2">
                <i data-lucide="user-plus" class="w-5 h-5 text-brand"></i>
                <span>Tambah Pegawai Baru</span>
            </h3>
            
            <form action="{{ route('admin.guru.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="nip" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">NIP / Username</label>
                    <input type="text" name="nip" id="nip" value="{{ old('nip') }}" placeholder="Masukkan NIP / Kode Pegawai" required 
                        class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark placeholder-gray-400 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                </div>

                <div>
                    <label for="nama_guru" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <input type="text" name="nama_guru" id="nama_guru" value="{{ old('nama_guru') }}" placeholder="Contoh: Drs. Budi, M.Pd" required
                        class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark placeholder-gray-400 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                </div>

                <div>
                    <label for="no_hp" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Nomor HP</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx"
                        class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark placeholder-gray-400 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                </div>

                <div>
                    <label for="kode_mapel" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Mata Pelajaran Utama</label>
                    <select name="kode_mapel" id="kode_mapel" 
                        class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all cursor-pointer">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach ($mapelList as $m)
                            <option value="{{ $m->kode_mapel }}" {{ old('kode_mapel') == $m->kode_mapel ? 'selected' : '' }}>
                                {{ $m->kode_mapel }} - {{ $m->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="role" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Role / Jabatan</label>
                    <select name="role" id="role" required
                        class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all cursor-pointer">
                        <option value="guru">Guru</option>
                        <option value="guru_piket">Guru Piket</option>
                        <option value="staf_tu">Staf TU</option>
                        <option value="satpam">Satpam</option>
                        <option value="kepala_sekolah">Kepala Sekolah</option>
                        <option value="wakasis_siswa">Wakil Kesiswaan (Siswa)</option>
                        <option value="wakasis_guru">Wakil Kesiswaan (Guru)</option>
                    </select>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Password Awal</label>
                    <input type="password" name="password" id="password" placeholder="Masukkan password akun login" required
                        class="block w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark placeholder-gray-400 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                </div>

                <button type="submit" class="w-full mt-2 py-3 px-4 bg-brand hover:bg-brand-hover text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center space-x-2 cursor-pointer">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Pegawai</span>
                </button>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR & FILTER PEGAWAI (2 Columns) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- FILTER CARD -->
            <div class="bg-white border border-[#19140015] rounded-2xl shadow-sm p-5">
                <form action="{{ route('admin.guru.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Cari Pegawai</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / NIP..." 
                                class="block w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark placeholder-gray-400 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                        </div>
                    </div>
                    
                    <div class="w-full sm:w-48">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Filter Mapel</label>
                        <select name="kode_mapel" 
                            class="block w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm text-dark focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all cursor-pointer">
                            <option value="">-- Semua Mapel --</option>
                            @foreach ($mapelList as $m)
                                <option value="{{ $m->kode_mapel }}" {{ request('kode_mapel') == $m->kode_mapel ? 'selected' : '' }}>
                                    {{ $m->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex space-x-2 w-full sm:w-auto">
                        <button type="submit" class="flex-1 sm:flex-initial px-5 py-2 bg-dark hover:bg-dark-hover text-white rounded-xl text-sm font-semibold transition-colors flex items-center justify-center space-x-1.5 cursor-pointer">
                            <i data-lucide="filter" class="w-4 h-4"></i>
                            <span>Filter</span>
                        </button>
                        @if (request('search') || request('kode_mapel'))
                            <a href="{{ route('admin.guru.index') }}" class="px-4 py-2 border border-gray-200 hover:bg-gray-50 text-gray-500 hover:text-dark rounded-xl text-sm font-semibold transition-colors flex items-center justify-center">
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
                                <th class="py-4 px-6">NIP / Username</th>
                                <th class="py-4 px-6">Nama Pegawai</th>
                                <th class="py-4 px-6">Jabatan</th>
                                <th class="py-4 px-6">No HP</th>
                                <th class="py-4 px-6">Mapel</th>
                                <th class="py-4 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#19140010] text-sm text-gray-600">
                            @forelse ($guruList as $g)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-6 font-semibold text-dark">{{ $g->nip }}</td>
                                    <td class="py-4 px-6 font-medium text-dark">{{ $g->nama_guru }}</td>
                                    <td class="py-4 px-6">
                                        <span class="px-2.5 py-1 bg-brand/10 text-brand text-xs font-semibold rounded-full uppercase">
                                            {{ str_replace('_', ' ', $g->jabatan ?? 'Guru') }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-gray-500">{{ $g->no_hp ?? '-' }}</td>
                                    <td class="py-4 px-6 font-semibold text-gray-700">{{ $g->kode_mapel ?? '-' }}</td>
                                    <td class="py-4 px-6 text-center">
                                        <form action="{{ route('admin.guru.destroy', $g->id_guru) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pegawai ini?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors cursor-pointer" title="Hapus Pegawai">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-400 italic">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                        Tidak ada data guru yang cocok dengan kriteria pencarian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION FOOTER -->
                @if ($guruList->hasPages())
                    <div class="px-6 py-4 bg-gray-50 border-t border-[#19140015] flex items-center justify-between">
                        <div class="text-xs text-gray-500">
                            Menampilkan halaman <span class="font-semibold text-dark">{{ $guruList->currentPage() }}</span> dari <span class="font-semibold text-dark">{{ $guruList->lastPage() }}</span>
                        </div>
                        <div class="inline-flex space-x-1.5">
                            @if ($guruList->previousPageUrl())
                                <a href="{{ $guruList->previousPageUrl() }}" class="px-3.5 py-1.5 border border-gray-250 bg-white hover:bg-gray-50 text-gray-600 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1">
                                    <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
                                    <span>Sebelumnya</span>
                                </a>
                            @endif
                            @if ($guruList->nextPageUrl())
                                <a href="{{ $guruList->nextPageUrl() }}" class="px-3.5 py-1.5 border border-gray-250 bg-white hover:bg-gray-50 text-gray-600 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1">
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