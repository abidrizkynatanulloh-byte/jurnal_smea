@extends('layouts.app')

@section('title', 'Manajemen Jadwal Mengajar - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Jadwal Mengajar</h1>
            <p class="text-xs text-gray-500 mt-1">
                Kelola dan petakan jadwal mengajar guru harian
            </p>
        </div>
    </div>

    <!-- Responsive Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- BAGIAN 1: FORM TAMBAH JADWAL MELEBAR -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-6 lg:sticky lg:top-8">
            <h3 class="font-bold text-[#1E2538] text-base mb-4 flex items-center space-x-2">
                <i data-lucide="calendar-plus" class="w-5 h-5 text-[#405078]"></i>
                <span>Tambah Jadwal Baru</span>
            </h3>
            
            <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="id_kelas" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Kelas</label>
                    <select name="id_kelas" id="id_kelas" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="hari" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Hari</label>
                    <select name="hari" id="hari" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="jam_mulai" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Jam Mulai (Ke-)</label>
                        <input type="number" name="jam_mulai" id="jam_mulai" min="1" max="15" value="{{ old('jam_mulai', 1) }}" required
                            class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                    </div>
                    <div>
                        <label for="jam_selesai" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Jam Selesai (Ke-)</label>
                        <input type="number" name="jam_selesai" id="jam_selesai" min="1" max="15" value="{{ old('jam_selesai', 2) }}" required
                            class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                    </div>
                </div>

                <div>
                    <label for="id_guru" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Guru Pengajar</label>
                    <select name="id_guru" id="id_guru" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                        <option value="">-- Pilih Guru --</option>
                        @foreach ($guruList as $g)
                            <option value="{{ $g->id_guru }}" {{ old('id_guru') == $g->id_guru ? 'selected' : '' }}>
                                {{ $g->nama_guru }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="kode_mapel" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Mata Pelajaran</label>
                    <select name="kode_mapel" id="kode_mapel" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach ($mapelList as $m)
                            <option value="{{ $m->kode_mapel }}" {{ old('kode_mapel') == $m->kode_mapel ? 'selected' : '' }}>
                                {{ $m->kode_mapel }} - {{ $m->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="id_ruangan" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Ruangan Kelas</label>
                    <select name="id_ruangan" id="id_ruangan" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach ($ruanganList as $r)
                            <option value="{{ $r->id_ruangan }}" {{ old('id_ruangan') == $r->id_ruangan ? 'selected' : '' }}>
                                {{ $r->nama_ruangan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full mt-2 py-3 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center space-x-2 cursor-pointer">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Jadwal</span>
                </button>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR & FILTER JADWAL -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- FILTER CARD -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-5">
                <form action="{{ route('admin.jadwal.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="w-full sm:w-48">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Filter Hari</label>
                        <select name="hari" 
                            class="block w-full px-3 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                            <option value="">-- Semua Hari --</option>
                            <option value="Senin" {{ request('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                            <option value="Selasa" {{ request('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                            <option value="Rabu" {{ request('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                            <option value="Kamis" {{ request('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                            <option value="Jumat" {{ request('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        </select>
                    </div>
                    
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Filter Kelas</label>
                        <select name="id_kelas" 
                            class="block w-full px-3 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                            <option value="">-- Semua Kelas --</option>
                            @foreach ($kelasList as $k)
                                <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex space-x-2 w-full sm:w-auto">
                        <button type="submit" class="flex-1 sm:flex-initial px-5 py-2 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-sm font-semibold transition-colors flex items-center justify-center space-x-1.5 cursor-pointer shadow-sm">
                            <i data-lucide="filter" class="w-4 h-4"></i>
                            <span>Filter</span>
                        </button>
                        @if (request('hari') || request('id_kelas'))
                            <a href="{{ route('admin.jadwal.index') }}" class="px-4 py-2 border border-[#D1D9EB] hover:bg-gray-50 text-gray-500 hover:text-[#1E2538] rounded-xl text-sm font-semibold transition-colors flex items-center justify-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- TABLE CARD -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#F8FAFC] text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                                <th class="py-4 px-6 w-28">Hari</th>
                                <th class="py-4 px-6">Jam Pelajaran</th>
                                <th class="py-4 px-6">Kelas</th>
                                <th class="py-4 px-6">Guru Pengajar</th>
                                <th class="py-4 px-6">Mata Pelajaran</th>
                                <th class="py-4 px-6">Ruangan</th>
                                <th class="py-4 px-6 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse ($jadwalList as $j)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-6"><span class="px-2.5 py-1 bg-[#405078]/10 text-[#405078] text-xs font-bold rounded-lg uppercase">{{ $j->hari }}</span></td>
                                    <td class="py-4 px-6 font-semibold text-[#1E2538]">Jam ke {{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
                                    <td class="py-4 px-6 font-bold text-gray-700">{{ $j->kelas ? $j->kelas->nama_kelas : '-' }}</td>
                                    <td class="py-4 px-6 font-medium text-[#1E2538]">{{ $j->guru ? $j->guru->nama_guru : '-' }}</td>
                                    <td class="py-4 px-6 text-gray-500">{{ $j->mapel ? $j->mapel->nama_mapel : '-' }}</td>
                                    <td class="py-4 px-6"><span class="inline-flex items-center space-x-1"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-gray-400"></i><span>{{ $j->ruangan ? $j->ruangan->nama_ruangan : '-' }}</span></span></td>
                                    <td class="py-4 px-6 text-center">
                                        <form action="{{ route('admin.jadwal.destroy', $j->id_jadwal) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus Jadwal">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-gray-400 italic">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                        Tidak ada jadwal mengajar yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION FOOTER -->
                @if ($jadwalList->hasPages())
                    <div class="px-6 py-4 bg-[#F8FAFC] border-t border-[#D1D9EB] flex items-center justify-between">
                        <div class="text-xs text-gray-500">
                            Menampilkan halaman <span class="font-semibold text-[#1E2538]">{{ $jadwalList->currentPage() }}</span> dari <span class="font-semibold text-[#1E2538]">{{ $jadwalList->lastPage() }}</span>
                        </div>
                        <div class="inline-flex space-x-1.5">
                            @if ($jadwalList->previousPageUrl())
                                <a href="{{ $jadwalList->previousPageUrl() }}" class="px-3.5 py-1.5 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-600 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1">
                                    <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
                                    <span>Sebelumnya</span>
                                </a>
                            @endif
                            @if ($jadwalList->nextPageUrl())
                                <a href="{{ $jadwalList->nextPageUrl() }}" class="px-3.5 py-1.5 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-600 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1">
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