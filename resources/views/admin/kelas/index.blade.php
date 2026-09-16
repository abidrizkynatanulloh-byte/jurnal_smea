@extends('layouts.app')

@section('title', 'Data Per Kelas - Admin Jurnal Esemkita')

@section('content')
<div class="space-y-5">
    <!-- Header Page & Title -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 bg-white dark:bg-[#242A35] p-4.5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-slate-100 tracking-tight flex items-center space-x-2">
                <i data-lucide="school" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                <span>Data Per Kelas</span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Kelola data kelas, alokasi wali kelas, dan lihat rincian siswa per kelas di SMK Negeri 1 Tulungagung.
            </p>
        </div>

        <div class="flex items-center space-x-2">
            <button type="button" onclick="openAddModal()" class="h-9 px-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-all flex items-center space-x-1.5 shadow-2xs cursor-pointer">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Kelas Baru</span>
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-3.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-xl text-xs font-semibold flex items-center space-x-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-3.5 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-xl text-xs font-semibold flex items-center space-x-2">
            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 dark:text-rose-400 shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

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

    <!-- Filter & Pencarian -->
    <div class="bg-white dark:bg-[#242A35] p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex items-center space-x-1.5 overflow-x-auto w-full sm:w-auto pb-1 sm:pb-0">
            <a href="{{ route('admin.kelas.index') }}" class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all {{ empty($tingkat) ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200' }}">
                Semua Kelas
            </a>
            <a href="{{ route('admin.kelas.index', ['tingkat' => 'X']) }}" class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all {{ $tingkat === 'X' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200' }}">
                Kelas X
            </a>
            <a href="{{ route('admin.kelas.index', ['tingkat' => 'XI']) }}" class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all {{ $tingkat === 'XI' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200' }}">
                Kelas XI
            </a>
            <a href="{{ route('admin.kelas.index', ['tingkat' => 'XII']) }}" class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all {{ $tingkat === 'XII' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200' }}">
                Kelas XII
            </a>
        </div>

        <form action="{{ route('admin.kelas.index') }}" method="GET" class="w-full sm:w-64 relative flex items-center">
            @if(!empty($tingkat))
                <input type="hidden" name="tingkat" value="{{ $tingkat }}">
            @endif
            <i data-lucide="search" class="w-4 h-4 text-slate-400 dark:text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama kelas..." 
                class="w-full h-9 pl-9 pr-3 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-600 transition-all">
        </form>
    </div>

    <!-- TABEL / GRID DATA KELAS -->
    <div class="bg-white dark:bg-[#242A35] border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
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
    <div class="bg-white dark:bg-[#242A35] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
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
                <select name="id_guru" id="add_id_guru" class="searchable-select w-full">
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
    <div class="bg-white dark:bg-[#242A35] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
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
                <select name="id_guru" id="edit_id_guru" class="searchable-select w-full">
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
    }

    function closeAddModal() {
        document.getElementById('modalAddKelas').classList.add('hidden');
    }

    function openEditModal(id, nama, idGuru) {
        document.getElementById('edit_nama_kelas').value = nama;
        document.getElementById('formEditKelas').action = '/admin/kelas/' + id;
        
        const selectGuru = document.getElementById('edit_id_guru');
        if (selectGuru) {
            selectGuru.value = idGuru || '';
            if (selectGuru.tomselect) {
                selectGuru.tomselect.setValue(idGuru || '');
            }
        }

        document.getElementById('modalEditKelas').classList.remove('hidden');
    }

    function closeEditModal() {
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
</script>
@endsection
