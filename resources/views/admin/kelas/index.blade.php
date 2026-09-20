@extends('layouts.app')

@section('title', 'Data Per Kelas - Admin Jurnal Esemkita')

@section('content')
<div class="space-y-5">
    <!-- Header Page & Title -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 sm:gap-6 bg-white dark:bg-[#242A35] p-5 sm:p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs">
        <div class="space-y-1">
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-slate-100 tracking-tight flex items-center space-x-2.5">
                <div class="p-2 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 shrink-0">
                    <i data-lucide="school" class="w-5.5 h-5.5"></i>
                </div>
                <span>Data Per Kelas</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed pt-0.5">
                Kelola data kelas, alokasi wali kelas, dan lihat rincian siswa per kelas di SMK Negeri 1 Tulungagung.
            </p>
        </div>

        <div class="flex items-center space-x-2 shrink-0 pt-1 md:pt-0 overflow-x-auto pb-1 sm:pb-0 scrollbar-none">
            <button type="button" onclick="openAddModal()" class="h-10 px-4 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shadow-2xs cursor-pointer whitespace-nowrap shrink-0">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>+ Tambah Kelas Baru</span>
            </button>
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-[#242A35] p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center justify-between shadow-2xs">
            <div>
                <p class="text-[11px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider">Total Kelas</p>
                <p class="text-2xl font-extrabold text-slate-900 dark:text-slate-100 mt-0.5">{{ $totalKelas }} <span class="text-xs font-medium text-slate-400">Kelas</span></p>
            </div>
            <div class="w-10 h-10 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center">
                <i data-lucide="building-2" class="w-5 h-5"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-[#242A35] p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center justify-between shadow-2xs">
            <div>
                <p class="text-[11px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider">Total Siswa Terdaftar</p>
                <p class="text-2xl font-extrabold text-slate-900 dark:text-slate-100 mt-0.5">{{ number_format($totalSiswa, 0, ',', '.') }} <span class="text-xs font-medium text-slate-400">Siswa</span></p>
            </div>
            <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center">
                <i data-lucide="users" class="w-5 h-5"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-[#242A35] p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center justify-between shadow-2xs">
            <div>
                <p class="text-[11px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider">Wali Kelas Terisi</p>
                <p class="text-2xl font-extrabold text-slate-900 dark:text-slate-100 mt-0.5">
                    {{ $daftarKelas->whereNotNull('wali_kelas')->where('wali_kelas', '!=', '')->count() }}
                    <span class="text-xs font-medium text-slate-400">/ {{ $totalKelas }} Kelas</span>
                </p>
            </div>
            <div class="w-10 h-10 bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 rounded-xl flex items-center justify-center">
                <i data-lucide="user-cog" class="w-5 h-5"></i>
            </div>
        </div>
    </div>

    <!-- Filter Bar & Search (Matches Reference 1:1) -->
    <div class="bg-white dark:bg-[#1C2433] p-3.5 sm:p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xs flex flex-col md:flex-row items-center justify-between gap-3.5">
        <!-- Left: Pill Filter Tabs (Semua Kelas, Kelas X, XI, XII) -->
        <div class="flex items-center space-x-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 shrink-0">
            <a href="{{ route('admin.kelas.index', array_filter(['search' => $search])) }}" 
                class="px-4 py-2 text-xs font-bold rounded-xl transition-all shrink-0 flex items-center space-x-1.5 {{ empty($tingkat) ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-[#232D3F] text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-[#2C384E]' }}">
                <span>Semua Kelas</span>
            </a>
            <a href="{{ route('admin.kelas.index', array_filter(['tingkat' => 'X', 'search' => $search])) }}" 
                class="px-4 py-2 text-xs font-bold rounded-xl transition-all shrink-0 flex items-center space-x-1.5 {{ $tingkat === 'X' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-[#232D3F] text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-[#2C384E]' }}">
                <span>Kelas X</span>
            </a>
            <a href="{{ route('admin.kelas.index', array_filter(['tingkat' => 'XI', 'search' => $search])) }}" 
                class="px-4 py-2 text-xs font-bold rounded-xl transition-all shrink-0 flex items-center space-x-1.5 {{ $tingkat === 'XI' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-[#232D3F] text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-[#2C384E]' }}">
                <span>Kelas XI</span>
            </a>
            <a href="{{ route('admin.kelas.index', array_filter(['tingkat' => 'XII', 'search' => $search])) }}" 
                class="px-4 py-2 text-xs font-bold rounded-xl transition-all shrink-0 flex items-center space-x-1.5 {{ $tingkat === 'XII' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-[#232D3F] text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-[#2C384E]' }}">
                <span>Kelas XII</span>
            </a>
        </div>

        <!-- Right: Search Form & View Mode Switcher -->
        <div class="flex items-center space-x-2.5 w-full md:w-auto justify-end">
            <!-- Search Form -->
            <form action="{{ route('admin.kelas.index') }}" method="GET" class="w-full md:w-64 relative flex items-center">
                @if(!empty($tingkat))
                    <input type="hidden" name="tingkat" value="{{ $tingkat }}">
                @endif
                <i data-lucide="search" class="w-4 h-4 text-slate-400 dark:text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama kelas..." 
                    class="w-full h-8 pl-8 pr-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                        </form>

            <!-- View Toggle Buttons -->
            <div class="flex items-center space-x-1 bg-slate-100 dark:bg-[#141C29] p-1 rounded-xl border border-slate-200 dark:border-slate-800 shrink-0">
                    <button type="button" onclick="setViewMode('grid')" id="btnViewGrid" class="h-8 px-3 rounded-lg text-xs font-bold transition-all bg-blue-600 text-white shadow-2xs flex items-center space-x-1.5 cursor-pointer">
                        <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">Grid Kelas</span>
                    </button>
                    <button type="button" onclick="setViewMode('table')" id="btnViewTable" class="h-8 px-3 rounded-lg text-xs font-semibold transition-all text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 flex items-center space-x-1.5 cursor-pointer">
                        <i data-lucide="table" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline">Tabel Sesi</span>
                    </button>
            </div>
        </div>
    </div>

    <!-- 1. GRID CARD VIEW (DESAIN TAMPILAN KELAS SEPERTI HARIAN PIKET) -->
    <div id="kelasGridView" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-6">
        @forelse ($daftarKelas as $idx => $k)
            @php
                $waliGuru = null;
                if ($k->wali_kelas) {
                    $waliGuru = $daftarGuru->first(function($g) use ($k) {
                        return $g->nip == $k->wali_kelas || $g->id_guru == $k->wali_kelas;
                    });
                }
            @endphp
            <div class="bg-white dark:bg-[#242A35] border-2 border-slate-200 dark:border-slate-700/80 rounded-2xl p-5 shadow-2xs hover:border-blue-500 dark:hover:border-blue-500 hover:shadow-md transition-all flex flex-col justify-between space-y-4 group">
                <div>
                    <!-- Header Kartu -->
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xs shrink-0 border border-blue-200/50 dark:border-blue-900/50 group-hover:scale-105 transition-transform">
                                <i data-lucide="school" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 dark:text-white text-base group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    {{ $k->nama_kelas }}
                                </h3>
                                <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block -mt-0.5">
                                    SMK Negeri 1 SMEA
                                </span>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-xl text-xs font-extrabold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700">
                            @if(Str::startsWith($k->nama_kelas, 'XII')) Kelas XII @elseif(Str::startsWith($k->nama_kelas, 'XI')) Kelas XI @else Kelas X @endif
                        </span>
                    </div>

                    <!-- Wali Kelas Box -->
                    <div class="mt-4 p-3.5 rounded-xl bg-slate-50 dark:bg-[#1A212D] border border-slate-200/80 dark:border-slate-700/70">
                        <div class="text-[10px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider mb-1 flex items-center justify-between">
                            <span>Wali Kelas</span>
                            <i data-lucide="user-cog" class="w-3.5 h-3.5 text-slate-400"></i>
                        </div>
                        @if($waliGuru)
                            <div class="font-bold text-xs sm:text-sm text-slate-800 dark:text-slate-200 flex items-center space-x-1.5 truncate">
                                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                                <span class="truncate" title="{{ $waliGuru->nama_guru }}">{{ $waliGuru->nama_guru }}</span>
                            </div>
                            <div class="text-[10.5px] text-slate-400 mt-0.5 font-mono">NIP: {{ $waliGuru->nip ?? '-' }}</div>
                        @else
                            <div class="text-xs font-semibold text-amber-600 dark:text-amber-400 flex items-center space-x-1.5">
                                <i data-lucide="alert-circle" class="w-4 h-4 text-amber-500 shrink-0"></i>
                                <span>Belum Ditentukan</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Stats Grid (Jumlah Siswa & Sesi KBM) -->
                <div class="grid grid-cols-2 gap-2.5 pt-2 border-t border-slate-100 dark:border-slate-800/80">
                    <div class="p-2.5 rounded-xl bg-blue-50/70 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/40 text-center">
                        <span class="block text-[10px] font-bold text-blue-600/80 dark:text-blue-400/80 uppercase tracking-wider">Siswa</span>
                        <span class="text-xs sm:text-sm font-extrabold text-blue-700 dark:text-blue-300">{{ $k->siswa_count }} Siswa</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-indigo-50/70 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/40 text-center">
                        <span class="block text-[10px] font-bold text-indigo-600/80 dark:text-indigo-400/80 uppercase tracking-wider">Jadwal KBM</span>
                        <span class="text-xs sm:text-sm font-extrabold text-indigo-700 dark:text-indigo-300">{{ $k->jadwal_count }} Sesi</span>
                    </div>
                </div>

                <!-- Action Buttons Footer (Button Lihat Siswa Lebih Tinggi & Sturdy) -->
                <div class="flex items-center space-x-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="showSiswaModal({{ $k->id_kelas }}, '{{ addslashes($k->nama_kelas) }}')"
                        class="flex-1 h-10.5 px-4 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white rounded-xl text-xs sm:text-sm font-extrabold transition-all flex items-center justify-center space-x-2 shadow-xs cursor-pointer">
                        <i data-lucide="users" class="w-4 h-4"></i>
                        <span>Lihat Siswa</span>
                    </button>

                    <button type="button" onclick="openEditModal({{ $k->id_kelas }}, '{{ addslashes($k->nama_kelas) }}', '{{ $waliGuru ? $waliGuru->id_guru : "" }}')"
                        class="h-10.5 w-10.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-semibold transition-all flex items-center justify-center shrink-0 cursor-pointer" title="Edit Kelas / Wali Kelas">
                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                    </button>

                    @if($k->siswa_count == 0)
                        <form action="{{ route('admin.kelas.destroy', $k->id_kelas) }}" method="POST" data-confirm="Hapus kelas {{ $k->nama_kelas }}?" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="h-10.5 w-10.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 text-rose-600 dark:text-rose-300 rounded-xl border border-rose-200 dark:border-rose-900 text-xs font-semibold transition-all flex items-center justify-center shrink-0 cursor-pointer" title="Hapus Kelas">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-[#242A35] p-10 rounded-2xl border border-slate-200 dark:border-slate-800 text-center">
                <i data-lucide="inbox" class="w-10 h-10 mx-auto text-slate-300 dark:text-slate-600 mb-2"></i>
                <p class="text-slate-500 dark:text-slate-400 font-semibold text-xs">Tidak ada data kelas yang ditemukan.</p>
            </div>
        @endforelse
    </div>

    <!-- 2. TABLE VIEW (OPSIONAL VIA TOGGLE) -->
    <div id="kelasTableView" class="hidden bg-white dark:bg-[#242A35] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 dark:bg-[#1A2836] border-b border-slate-200 dark:border-slate-800 text-slate-600 dark:text-blue-300 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-4 w-12 text-center">NO</th>
                        <th class="py-3 px-4 w-48">NAMA KELAS</th>
                        <th class="py-3 px-4">WALI KELAS</th>
                        <th class="py-3 px-4 text-center w-32">JUMLAH SISWA</th>
                        <th class="py-3 px-4 text-center w-32">JADWAL KBM</th>
                        <th class="py-3 px-4 text-center w-64">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse ($daftarKelas as $idx => $k)
                        @php
                            $waliGuru = null;
                            if ($k->wali_kelas) {
                                $waliGuru = $daftarGuru->first(function($g) use ($k) {
                                    return $g->nip == $k->wali_kelas || $g->id_guru == $k->wali_kelas;
                                });
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-3 px-4 text-center font-mono text-slate-400 text-xs">{{ $idx + 1 }}</td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900 dark:text-slate-100 text-xs">{{ $k->nama_kelas }}</div>
                            </td>
                            <td class="py-3 px-4">
                                @if($waliGuru)
                                    <div class="font-semibold text-slate-800 dark:text-slate-200 text-xs flex items-center space-x-1.5">
                                        <i data-lucide="user-check" class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400 shrink-0"></i>
                                        <span>{{ $waliGuru->nama_guru }}</span>
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">NIP: {{ $waliGuru->nip ?? '-' }}</div>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10.5px] font-semibold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800">
                                        Belum Ditentukan
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 rounded-lg text-xs font-bold">
                                    {{ $k->siswa_count }} Siswa
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold">
                                    {{ $k->jadwal_count }} Sesi KBM
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <button type="button" onclick="showSiswaModal({{ $k->id_kelas }}, '{{ addslashes($k->nama_kelas) }}')" 
                                        class="h-7 px-2.5 bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/40 dark:hover:bg-blue-900/60 text-blue-700 dark:text-blue-300 rounded-lg border border-blue-200 dark:border-blue-800 text-xs font-semibold transition-colors cursor-pointer inline-flex items-center space-x-1">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        <span>Lihat Siswa</span>
                                    </button>

                                    <button type="button" onclick="openEditModal({{ $k->id_kelas }}, '{{ addslashes($k->nama_kelas) }}', '{{ $waliGuru ? $waliGuru->id_guru : "" }}')" 
                                        class="h-7 px-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-semibold transition-colors cursor-pointer inline-flex items-center space-x-1">
                                        <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                                        <span>Edit</span>
                                    </button>

                                    @if($k->siswa_count == 0)
                                        <form action="{{ route('admin.kelas.destroy', $k->id_kelas) }}" method="POST" data-confirm="Hapus kelas {{ $k->nama_kelas }}?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="h-7 px-2 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 text-rose-600 dark:text-rose-300 rounded-lg border border-rose-200 dark:border-rose-900 text-xs font-semibold transition-colors cursor-pointer">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 dark:text-slate-400 italic text-xs">
                                <i data-lucide="inbox" class="w-6 h-6 mx-auto mb-1 text-slate-300 dark:text-slate-600"></i>
                                Tidak ada data kelas yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH KELAS -->
<div id="modalAddKelas" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-[#242A35] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl w-full max-w-md">
        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between rounded-t-2xl">
            <h3 class="font-bold text-slate-900 dark:text-slate-100 text-sm flex items-center space-x-2">
                <i data-lucide="plus-circle" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i>
                <span>Tambah Kelas Baru</span>
            </h3>
            <button type="button" onclick="closeAddModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('admin.kelas.store') }}" method="POST" class="p-5 space-y-4">
            @csrf

            <div>
                <label for="add_nama_kelas" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Nama Kelas</label>
                <input type="text" name="nama_kelas" id="add_nama_kelas" placeholder="Contoh: X BD 3, X ULW, XI RPL 1..." required
                    class="w-full h-9 px-3 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-blue-600">
            </div>

            <div>
                <label for="add_id_guru" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Pilih Wali Kelas (Opsional)</label>
                <select name="id_guru" id="add_id_guru" class="searchable-select w-full" placeholder="Cari / pilih wali kelas...">
                    <option value="">-- Belum Ditentukan --</option>
                    @foreach($daftarGuru as $g)
                        <option value="{{ $g->id_guru }}">{{ $g->nama_guru }} (NIP: {{ $g->nip ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-2 flex justify-end space-x-2">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg text-xs font-semibold hover:bg-slate-200 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-colors">
                    Simpan Kelas
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT KELAS -->
<div id="modalEditKelas" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-[#242A35] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl w-full max-w-md">
        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between rounded-t-2xl">
            <h3 class="font-bold text-slate-900 dark:text-slate-100 text-sm flex items-center space-x-2">
                <i data-lucide="edit-3" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i>
                <span>Edit Data Kelas & Wali Kelas</span>
            </h3>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="formEditKelas" action="" method="POST" class="p-5 space-y-4" data-confirm="Apakah Anda yakin ingin memperbarui data kelas ini?">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_nama_kelas" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Nama Kelas</label>
                <input type="text" name="nama_kelas" id="edit_nama_kelas" required
                    class="w-full h-9 px-3 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-blue-600">
            </div>

            <div>
                <label for="edit_id_guru" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1">Pilih Wali Kelas</label>
                <select name="id_guru" id="edit_id_guru" class="searchable-select w-full" placeholder="Cari / pilih wali kelas...">
                    <option value="">-- Belum Ditentukan --</option>
                    @foreach($daftarGuru as $g)
                        <option value="{{ $g->id_guru }}">{{ $g->nama_guru }} (NIP: {{ $g->nip ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-2 flex justify-end space-x-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg text-xs font-semibold hover:bg-slate-200 transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DAFTAR SISWA PER KELAS -->
<div id="modalSiswaKelas" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-[#242A35] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl w-full max-w-3xl overflow-hidden max-h-[90vh] flex flex-col">
        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between shrink-0">
            <div>
                <h3 class="font-bold text-slate-900 dark:text-slate-100 text-sm flex items-center space-x-2">
                    <i data-lucide="graduation-cap" class="w-4.5 h-4.5 text-blue-600 dark:text-blue-400"></i>
                    <span>Daftar Siswa - <span id="modalTitleKelas" class="text-blue-600 dark:text-blue-400"></span></span>
                </h3>
                <p class="text-[11px] text-slate-400 mt-0.5" id="modalMetaKelas"></p>
            </div>
            <button type="button" onclick="closeSiswaModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="p-4 overflow-y-auto flex-1">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-[#1A2836] border-b border-slate-200 dark:border-slate-800 text-slate-600 dark:text-blue-300 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-2.5 px-3 w-10 text-center">NO</th>
                            <th class="py-2.5 px-3 w-32">NISN</th>
                            <th class="py-2.5 px-3 w-28">NIS</th>
                            <th class="py-2.5 px-3">NAMA SISWA</th>
                            <th class="py-2.5 px-3 text-center w-24">GENDER</th>
                        </tr>
                    </thead>
                    <tbody id="siswaTableBody" class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                        <!-- AJAX content -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="px-5 py-3 border-t border-slate-200 dark:border-slate-800 flex justify-between items-center shrink-0">
            <span class="text-xs font-semibold text-slate-500" id="totalSiswaModalText">0 Siswa</span>
            <button type="button" onclick="closeSiswaModal()" class="px-4 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg text-xs font-semibold hover:bg-slate-200 transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('modalAddKelas').classList.remove('hidden');
        const selectGuru = document.getElementById('add_id_guru');
        if (selectGuru) {
            if (selectGuru.tomselect) {
                selectGuru.tomselect.destroy();
            }
            selectGuru.value = '';
            if (typeof TomSelect !== 'undefined') {
                new TomSelect(selectGuru, {
                    create: false,
                    maxOptions: 250,
                    placeholder: 'Cari / pilih wali kelas...',
                    allowEmptyOption: true,
                    onItemAdd: function() {
                        this.blur();
                    }
                });
            }
        }
    }

    function closeAddModal() {
        const selectGuru = document.getElementById('add_id_guru');
        if (selectGuru && selectGuru.tomselect) selectGuru.tomselect.close();
        document.getElementById('modalAddKelas').classList.add('hidden');
    }

    function openEditModal(id, nama, idGuru) {
        document.getElementById('edit_nama_kelas').value = nama;
        document.getElementById('formEditKelas').action = '/admin/kelas/' + id;
        document.getElementById('modalEditKelas').classList.remove('hidden');
        
        const selectGuru = document.getElementById('edit_id_guru');
        if (selectGuru) {
            if (selectGuru.tomselect) {
                selectGuru.tomselect.destroy();
            }
            selectGuru.value = idGuru || '';
            if (typeof TomSelect !== 'undefined') {
                const ts = new TomSelect(selectGuru, {
                    create: false,
                    maxOptions: 250,
                    placeholder: 'Cari / pilih wali kelas...',
                    allowEmptyOption: true,
                    onItemAdd: function() {
                        this.blur();
                    }
                });
                if (idGuru) {
                    ts.setValue(idGuru, true);
                }
            }
        }
    }

    function closeEditModal() {
        const selectGuru = document.getElementById('edit_id_guru');
        if (selectGuru && selectGuru.tomselect) selectGuru.tomselect.close();
        document.getElementById('modalEditKelas').classList.add('hidden');
    }

    function showSiswaModal(idKelas, namaKelas) {
        document.getElementById('modalTitleKelas').innerText = namaKelas;
        document.getElementById('modalMetaKelas').innerText = 'Memuat data siswa...';
        document.getElementById('siswaTableBody').innerHTML = '<tr><td colspan="5" class="py-6 text-center text-slate-400 italic">Memuat data siswa...</td></tr>';
        document.getElementById('modalSiswaKelas').classList.remove('hidden');

        fetch('/admin/kelas/' + idKelas + '/siswa')
            .then(res => res.json())
            .then(data => {
                document.getElementById('modalMetaKelas').innerText = 'Wali Kelas: ' + (data.wali_kelas || 'Belum Ada') + ' • Total: ' + data.total + ' Siswa';
                document.getElementById('totalSiswaModalText').innerText = data.total + ' Siswa Terdaftar';

                if (data.siswa.length === 0) {
                    document.getElementById('siswaTableBody').innerHTML = '<tr><td colspan="5" class="py-6 text-center text-slate-400 italic">Belum ada data siswa di kelas ini.</td></tr>';
                    return;
                }

                let html = '';
                data.siswa.forEach((s, idx) => {
                    let genderBadge = s.jenis_kelamin === 'L' 
                        ? '<span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-300 rounded font-bold text-[10px]">Laki-laki</span>'
                        : '<span class="px-2 py-0.5 bg-pink-50 dark:bg-pink-950/50 text-pink-600 dark:text-pink-300 rounded font-bold text-[10px]">Perempuan</span>';

                    html += `<tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="py-2.5 px-3 text-center font-mono text-slate-400">${idx + 1}</td>
                        <td class="py-2.5 px-3 font-mono font-semibold">${s.nisn || '-'}</td>
                        <td class="py-2.5 px-3 font-mono">${s.nis}</td>
                        <td class="py-2.5 px-3 font-bold text-slate-800 dark:text-slate-100">${s.nama_siswa}</td>
                        <td class="py-2.5 px-3 text-center">${genderBadge}</td>
                    </tr>`;
                });

                document.getElementById('siswaTableBody').innerHTML = html;
            })
            .catch(err => {
                document.getElementById('siswaTableBody').innerHTML = '<tr><td colspan="5" class="py-6 text-center text-rose-500 font-semibold">Gagal memuat data siswa.</td></tr>';
            });
    }

    function closeSiswaModal() {
        document.getElementById('modalSiswaKelas').classList.add('hidden');
    }

    function setViewMode(mode) {
        const gridView = document.getElementById('kelasGridView');
        const tableView = document.getElementById('kelasTableView');
        const btnGrid = document.getElementById('btnViewGrid');
        const btnTable = document.getElementById('btnViewTable');

        if (!gridView || !tableView) return;

        if (mode === 'grid') {
            gridView.classList.remove('hidden');
            tableView.classList.add('hidden');
            if (btnGrid) btnGrid.className = 'h-7.5 px-2.5 rounded-lg text-xs font-bold transition-all bg-blue-600 text-white shadow-2xs flex items-center space-x-1 cursor-pointer';
            if (btnTable) btnTable.className = 'h-7.5 px-2.5 rounded-lg text-xs font-semibold transition-all text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 flex items-center space-x-1 cursor-pointer';
            localStorage.setItem('kelasViewMode', 'grid');
        } else {
            gridView.classList.add('hidden');
            tableView.classList.remove('hidden');
            if (btnTable) btnTable.className = 'h-7.5 px-2.5 rounded-lg text-xs font-bold transition-all bg-blue-600 text-white shadow-2xs flex items-center space-x-1 cursor-pointer';
            if (btnGrid) btnGrid.className = 'h-7.5 px-2.5 rounded-lg text-xs font-semibold transition-all text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 flex items-center space-x-1 cursor-pointer';
            localStorage.setItem('kelasViewMode', 'table');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const savedView = localStorage.getItem('kelasViewMode');
        if (savedView === 'table') {
            setViewMode('table');
        }
    });
</script>
@endsection
