@extends('layouts.app')

@section('title', 'Data Siswa & Rombel - Jurnal Esemkita')

@section('content')
<div class="flex-1 flex flex-col min-h-0 space-y-2.5">
    <!-- Header Halaman (Proporsi Gambar 1 - FIXED) -->
    <div class="shrink-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div class="flex items-center space-x-2.5">
            <h1 class="text-lg font-bold text-slate-900 tracking-tight">Data Siswa & Rombel</h1>
            <span class="px-2 py-0.5 text-[11px] font-semibold bg-slate-100 text-slate-700 rounded-md font-mono tabular-nums border border-slate-200">
                {{ number_format($totalSiswa, 0, ',', '.') }} Siswa
            </span>
        </div>
        <div class="flex items-center space-x-1.5">
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="h-8 px-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer">
                <i data-lucide="panel-left-close" class="w-3.5 h-3.5 text-slate-500"></i>
                <span id="btnToggleFormText">Sembunyikan Form Tambah</span>
            </button>
            <a href="{{ route('admin.siswa.trash') }}" class="h-8 px-3 border border-rose-200 bg-rose-50/80 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                <span>Sampah</span>
            </a>
        </div>
    </div>

    <!-- Layout Grid Utama (Full Viewport Height - Proporsi Gambar 1) -->
    <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-3 gap-3.5 items-stretch" id="masterDataGrid">
        
        <!-- BAGIAN 1: FORM TAMBAH SISWA (UKURAN KOMPAK SESUAI GAMBAR 1) -->
        <div id="formCol" class="bg-white border border-slate-200/90 rounded-xl p-3.5 shadow-2xs space-y-2.5 overflow-y-auto h-full max-h-full">
            <div class="flex items-center space-x-2 pb-2 border-b border-slate-100 shrink-0">
                <div class="w-5 h-5 rounded-md bg-[#1E2538] text-white flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-3 h-3"></i>
                </div>
                <span class="font-bold text-xs text-slate-900 tracking-tight uppercase">TAMBAH SISWA BARU</span>
            </div>

            <form action="{{ route('admin.siswa.store') }}" method="POST" class="space-y-2">
                @csrf

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Nomor Induk Siswa (NIS) *</label>
                    <input type="text" name="nis" value="{{ old('nis') }}" placeholder="Contoh: 23456" required
                        class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 font-mono transition-colors">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Nomor Induk Siswa Nasional (NISN) *</label>
                    <input type="text" name="nisn" value="{{ old('nisn') }}" placeholder="10 Digit NISN (Akun Login Siswa)" required
                        class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 font-mono transition-colors">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Nama Lengkap Siswa *</label>
                    <input type="text" name="nama_siswa" value="{{ old('nama_siswa') }}" placeholder="Contoh: Ahmad Rizki Pratama" required
                        class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Jenis Kelamin *</label>
                        <select name="jenis_kelamin" required
                            class="w-full h-8 px-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Rombel / Kelas *</label>
                        <select name="id_kelas" required
                            class="w-full h-8 px-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelasList as $k)
                                <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Nomor WhatsApp Wali Siswa</label>
                    <input type="text" name="no_hp_wali" value="{{ old('no_hp_wali') }}" placeholder="08xxxxxxxxxx (Notifikasi WA)"
                        class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 font-mono transition-colors">
                </div>

                <div class="pt-1.5">
                    <button type="submit" class="w-full h-10.5 bg-[#1E2538] hover:bg-[#121724] text-white rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center justify-center space-x-2 shadow-xs cursor-pointer">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Simpan Data Siswa</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: DATA TABLE CARD (SESUAI GAMBAR 1 & GAMBAR 2) -->
        <div id="tableCol" class="lg:col-span-2 flex flex-col h-full min-h-0">
            <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden flex flex-col h-full min-h-0">
                <!-- Top Control Bar (Search + Filter Kelas + Filter Kelamin) -->
                <div class="shrink-0 px-3.5 py-2 border-b border-slate-200 bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <form action="{{ route('admin.siswa.index') }}" method="GET" class="flex flex-1 flex-col sm:flex-row items-center gap-2 w-full">
                        <div class="relative flex-1 w-full">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="search" id="liveSearchInput" value="{{ request('search') }}" placeholder="Cari Nama Siswa, NIS, atau NISN..." 
                                class="w-full h-8 pl-8 pr-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                        </div>

                        <div class="w-full sm:w-36">
                            <select name="id_kelas" onchange="this.form.submit()" class="w-full h-8 px-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                                <option value="">Semua Kelas</option>
                                @foreach ($kelasList as $k)
                                    <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-full sm:w-32">
                            <select name="jenis_kelamin" onchange="this.form.submit()" class="w-full h-8 px-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                                <option value="">Semua Kelamin</option>
                                <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                                <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                            </select>
                        </div>

                        @if (request('search') || request('id_kelas') || request('jenis_kelamin'))
                            <a href="{{ route('admin.siswa.index') }}" class="h-8 px-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-semibold flex items-center transition-colors shrink-0">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Table Body (INTERNAL SCROLL SESUAI GAMBAR 1) -->
                <div class="flex-1 overflow-y-auto overflow-x-auto min-h-0">
                    <table class="w-full text-left border-collapse text-xs" id="tableSiswa">
                        <thead class="sticky top-0 bg-white border-b border-slate-200 z-10 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="py-2.5 px-3 text-center w-12 bg-white">NO</th>
                                <th class="py-2.5 px-3 w-24 bg-white">NIS</th>
                                <th class="py-2.5 px-3 w-28 bg-white">NISN</th>
                                <th class="py-2.5 px-3 bg-white">NAMA LENGKAP SISWA</th>
                                <th class="py-2.5 px-2 text-center w-12 bg-white">L/P</th>
                                <th class="py-2.5 px-3 w-28 bg-white">KELAS</th>
                                <th class="py-2.5 px-3 w-32 bg-white">NO. HP WALI</th>
                                <th class="py-2.5 px-3 text-center w-20 bg-white">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700" id="siswaTbody">
                            @include('admin.siswa.partials.rows')
                        </tbody>
                    </table>
                </div>

                <!-- Bottom Pagination Bar (Sesuai Gambar 1 & Gambar 2) -->
                <div class="shrink-0" id="paginationContainer">
                    <x-pagination-bar :paginator="$siswaList" />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT SISWA (Proporsional & Lebih Lega Sesuai Permintaan) -->
<div id="modalEditSiswa" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white dark:bg-[#151B26] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl max-w-lg md:max-w-xl w-full p-6 sm:p-7 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <span class="font-bold text-slate-900 dark:text-slate-100 text-sm uppercase tracking-tight flex items-center space-x-2">
                <div class="w-7 h-7 rounded-lg bg-[#1E2538] text-white flex items-center justify-center">
                    <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                </div>
                <span>Edit Data Siswa</span>
            </span>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="formEditSiswa" method="POST" class="space-y-3.5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor Induk Siswa (NIS) *</label>
                <input type="text" name="nis" id="edit_nis" required readonly
                    class="w-full h-10 px-3 bg-slate-50 dark:bg-[#1A2230]/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-600 dark:text-slate-400 font-mono focus:outline-none cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor Induk Siswa Nasional (NISN) *</label>
                <input type="text" name="nisn" id="edit_nisn" required
                    class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 font-mono focus:outline-none focus:border-slate-800 transition-colors">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap Siswa *</label>
                <input type="text" name="nama_siswa" id="edit_nama" required
                    class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-slate-800 transition-colors">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" id="edit_jk" required
                        class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Rombel / Kelas *</label>
                    <select name="id_kelas" id="edit_kelas" required
                        class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                        @foreach ($kelasList as $k)
                            <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor WhatsApp Wali Siswa</label>
                <input type="text" name="no_hp_wali" id="edit_no_hp_wali" placeholder="08xxxxxxxxxx"
                    class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 font-mono focus:outline-none focus:border-slate-800 transition-colors">
            </div>

            <div class="flex items-center justify-end space-x-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeEditModal()" class="h-10 px-4.5 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-10 px-5.5 bg-[#1E2538] hover:bg-[#121724] text-white rounded-xl text-xs sm:text-sm font-bold transition-colors cursor-pointer shadow-xs">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL KONFIRMASI HAPUS SISWA DENGAN ALASAN -->
<div id="modalDeleteSiswa" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white dark:bg-[#151B26] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 flex items-center justify-center shrink-0">
                <i data-lucide="trash-2" class="w-5 h-5 text-rose-600 dark:text-rose-400"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 dark:text-slate-100 text-sm tracking-tight">Pindahkan Siswa ke Sampah</h3>
                <p class="text-[11.5px] text-slate-500 dark:text-slate-400 font-medium mt-0.5" id="deleteSiswaNamaTarget"></p>
            </div>
        </div>

        <form id="formDeleteSiswa" method="POST" class="space-y-3.5 pt-1">
            @csrf
            @method('DELETE')

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Alasan Penghapusan / Dipindahkan *</label>
                <div class="space-y-2 text-xs">
                    <label class="flex items-center space-x-3 px-3 py-2.5 bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/70 rounded-xl cursor-pointer hover:border-slate-300 dark:hover:border-slate-600 transition-colors">
                        <input type="radio" name="alasan_preset_siswa" value="Lulus / Tamat Belajar" onchange="setAlasanSiswa(this.value)" checked class="w-4 h-4 accent-rose-600 cursor-pointer">
                        <span class="font-semibold text-slate-800 dark:text-slate-200">Lulus / Tamat Belajar</span>
                    </label>
                    <label class="flex items-center space-x-3 px-3 py-2.5 bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/70 rounded-xl cursor-pointer hover:border-slate-300 dark:hover:border-slate-600 transition-colors">
                        <input type="radio" name="alasan_preset_siswa" value="Pindah Sekolah / Mutasi" onchange="setAlasanSiswa(this.value)" class="w-4 h-4 accent-rose-600 cursor-pointer">
                        <span class="font-semibold text-slate-800 dark:text-slate-200">Pindah Sekolah / Mutasi</span>
                    </label>
                    <label class="flex items-center space-x-3 px-3 py-2.5 bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/70 rounded-xl cursor-pointer hover:border-slate-300 dark:hover:border-slate-600 transition-colors">
                        <input type="radio" name="alasan_preset_siswa" value="Dikeluarkan / Drop Out (DO)" onchange="setAlasanSiswa(this.value)" class="w-4 h-4 accent-rose-600 cursor-pointer">
                        <span class="font-semibold text-slate-800 dark:text-slate-200">Dikeluarkan / Drop Out (DO)</span>
                    </label>
                    <label class="flex items-center space-x-3 px-3 py-2.5 bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/70 rounded-xl cursor-pointer hover:border-slate-300 dark:hover:border-slate-600 transition-colors">
                        <input type="radio" name="alasan_preset_siswa" value="" onchange="setAlasanSiswa('')" class="w-4 h-4 accent-rose-600 cursor-pointer">
                        <span class="font-semibold text-slate-800 dark:text-slate-200">Lainnya (Ketik Manual...)</span>
                    </label>
                </div>
            </div>

            <div>
                <input type="text" name="alasan_hapus" id="inputAlasanSiswa" value="Lulus / Tamat Belajar" placeholder="Tuliskan alasan detail..." required
                    class="w-full h-10 px-3.5 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:border-rose-500 transition-colors shadow-2xs">
            </div>

            <div class="flex items-center justify-end space-x-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeDeleteSiswaModal()" class="h-10 px-4.5 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-10 px-6 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition-colors cursor-pointer shadow-sm whitespace-nowrap shrink-0">
                    Ya, Pindahkan ke Sampah
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let isFormFolded = false;
    function toggleFormPanel() {
        const formCol = document.getElementById('formCol');
        const tableCol = document.getElementById('tableCol');
        const btnText = document.getElementById('btnToggleFormText');

        if (!isFormFolded) {
            formCol.style.display = 'none';
            tableCol.className = 'lg:col-span-3 flex flex-col h-full min-h-0';
            btnText.innerText = 'Buka Form Tambah';
            isFormFolded = true;
        } else {
            formCol.style.display = 'block';
            tableCol.className = 'lg:col-span-2 flex flex-col h-full min-h-0';
            btnText.innerText = 'Sembunyikan Form Tambah';
            isFormFolded = false;
        }
    }

    function openEditModal(nis, nama, nisn, idKelas, jk, noHpWali) {
        document.getElementById('edit_nis').value = nis;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_nisn').value = nisn;
        document.getElementById('edit_kelas').value = idKelas;
        document.getElementById('edit_jk').value = jk;
        document.getElementById('edit_no_hp_wali').value = noHpWali;

        document.getElementById('formEditSiswa').action = `/admin/siswa/${nis}`;
        document.getElementById('modalEditSiswa').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modalEditSiswa').classList.add('hidden');
    }

    function openDeleteSiswaModal(nis, nama) {
        document.getElementById('deleteSiswaNamaTarget').innerText = `Siswa: ${nama} (NIS: ${nis})`;
        document.getElementById('formDeleteSiswa').action = `/admin/siswa/${nis}`;
        document.getElementById('inputAlasanSiswa').value = 'Lulus / Tamat Belajar';
        document.getElementById('modalDeleteSiswa').classList.remove('hidden');
    }

    function closeDeleteSiswaModal() {
        document.getElementById('modalDeleteSiswa').classList.add('hidden');
    }

    function setAlasanSiswa(val) {
        const input = document.getElementById('inputAlasanSiswa');
        if (val) {
            input.value = val;
        } else {
            input.value = '';
            input.focus();
        }
    }

    // Dynamic AJAX Live Search Across All 1,712 Students (Zero Page Reloads)
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('liveSearchInput');
        const searchForm = searchInput ? searchInput.closest('form') : null;
        let ajaxTimer = null;

        function performAjaxSearch() {
            if (!searchInput) return;
            const q = searchInput.value.trim();
            const form = searchForm || searchInput.form;

            const formData = new FormData(form);
            formData.set('search', q);
            const params = new URLSearchParams(formData);

            fetch(`{{ route('admin.siswa.index') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('siswaTbody');
                const pag = document.getElementById('paginationContainer');
                if (tbody && data.html) {
                    tbody.innerHTML = data.html;
                }
                if (pag && data.pagination) {
                    pag.innerHTML = data.pagination;
                }
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            })
            .catch(err => console.error('AJAX Live Search Error:', err));
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const q = this.value.trim();
                const qLower = q.toLowerCase();

                // 1. Instant client-side visual filter on current DOM rows (0ms delay)
                const rows = document.querySelectorAll('.siswa-row');
                rows.forEach(row => {
                    const text = row.getAttribute('data-search') || '';
                    if (!qLower || text.includes(qLower)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // 2. Fast AJAX query (150ms debounce) searching ALL 1,712 students without page reloads
                clearTimeout(ajaxTimer);
                ajaxTimer = setTimeout(() => {
                    performAjaxSearch();
                }, 150);
            });

            // Prevent form submit refresh on Enter
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(ajaxTimer);
                    performAjaxSearch();
                }
            });
        }
    });
</script>
@endsection