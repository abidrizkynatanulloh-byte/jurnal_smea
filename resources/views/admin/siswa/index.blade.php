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
        <div class="flex items-center space-x-2 shrink-0 overflow-x-auto pb-1 sm:pb-0 scrollbar-none">
            <button type="button" onclick="openTambahSiswaModal()"
                class="h-10 px-4 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shadow-2xs cursor-pointer whitespace-nowrap shrink-0">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                <span>+ Tambah Siswa</span>
            </button>
            <button type="button" onclick="openImportSiswaModal()"
                class="h-10 px-4 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shadow-2xs cursor-pointer whitespace-nowrap shrink-0">
                <i data-lucide="file-up" class="w-4 h-4"></i>
                <span>Import CSV</span>
            </button>
            <a href="{{ route('admin.siswa.trash') }}" class="h-10 px-4 border border-rose-200 bg-rose-50/90 hover:bg-rose-100 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-900 rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shadow-2xs whitespace-nowrap shrink-0">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
                <span>Sampah</span>
            </a>
            <form action="{{ route('admin.siswa.reset') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENGHAPUS SELURUH data siswa & akun wali murid dari database? Action ini akan mengosongkan tabel siswa.');" class="inline">
                @csrf
                <button type="submit" class="h-10 px-4 bg-red-600 hover:bg-red-700 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all flex items-center space-x-2 shadow-2xs cursor-pointer whitespace-nowrap shrink-0">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    <span>Kosongkan DB</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Layout Flex Utama (Full Viewport Height) -->
    <div class="flex-1 min-h-0 flex flex-col" id="masterDataGrid">
        <!-- DATA TABLE CARD -->
        <div id="tableCol" class="flex-1 min-w-0 flex flex-col h-full min-h-0">
            <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden flex flex-col h-full min-h-0">
                <!-- Top Control Bar (Search + Filter Kelas + Filter Kelamin) -->
                <div class="shrink-0 px-3 py-1.5 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-[#1E2538] flex items-center justify-between gap-2 overflow-x-auto no-scrollbar">
                    <form action="{{ route('admin.siswa.index') }}" method="GET" class="flex flex-1 flex-row items-center gap-2 w-full min-w-0">
                        <div class="relative flex-1 min-w-[120px]">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="search" id="liveSearchInput" value="{{ request('search') }}" placeholder="Cari Nama Siswa, NIS, atau NISN..." 
                                class="w-full h-8 pl-8 pr-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-slate-800 transition-colors">
                        </div>

                        <div class="w-32 sm:w-36 shrink-0">
                            <select name="id_kelas" onchange="this.form.submit()" class="w-full h-8 pl-2.5 pr-7 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-200 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                                <option value="">Semua Kelas</option>
                                @foreach ($kelasList as $k)
                                    <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-32 sm:w-36 shrink-0">
                            <select name="jenis_kelamin" onchange="this.form.submit()" class="w-full h-8 pl-2.5 pr-7 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-200 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                                <option value="">Semua Kelamin</option>
                                <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                                <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                            </select>
                        </div>

                        @if (request('search') || request('id_kelas') || request('jenis_kelamin'))
                            <a href="{{ route('admin.siswa.index') }}" class="h-8 px-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg text-xs font-semibold flex items-center transition-colors shrink-0">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Table Body -->
                <div class="flex-1 overflow-y-auto overflow-x-auto min-h-0">
                    <table class="w-full text-left border-collapse text-xs" id="tableSiswa">
                        <thead class="sticky top-0 bg-white border-b border-slate-200 z-10 text-[10.5px] font-bold text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="py-2 px-2.5 text-center w-10 bg-white">NO</th>
                                <th class="py-2 px-2.5 w-20 bg-white">NIS</th>
                                <th class="py-2 px-2.5 w-24 bg-white">NISN</th>
                                <th class="py-2 px-2.5 bg-white">NAMA LENGKAP SISWA</th>
                                <th class="py-2 px-2 text-center w-12 bg-white">L/P</th>
                                <th class="py-2 px-2.5 w-24 bg-white">KELAS</th>
                                <th class="py-2 px-2.5 w-36 bg-white">TEMPAT, TGL LAHIR</th>
                                <th class="py-2 px-2.5 w-28 bg-white">NO. HP WALI</th>
                                <th class="py-2 px-2.5 text-center w-16 bg-white">AKSI</th>
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

        <form id="formEditSiswa" method="POST" class="space-y-3.5" data-confirm="Apakah Anda yakin ingin memperbarui data siswa ini?">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor Induk Siswa (NIS) <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <input type="text" name="nis" id="edit_nis" placeholder="Boleh dikosongkan..."
                    class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 font-mono focus:outline-none focus:border-slate-800 transition-colors">
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

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tempat Lahir</label>
                    <input type="text" name="kota_lahir" id="edit_kota_lahir" placeholder="Misal: Boyolali"
                        class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-slate-800 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="edit_tanggal_lahir"
                        class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-slate-800 transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor WhatsApp Wali Siswa</label>
                <input type="text" name="no_hp_wali" id="edit_no_hp_wali" placeholder="08xxxxxxxxxx"
                    class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 font-mono focus:outline-none focus:border-slate-800 transition-colors">
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeEditModal()" class="h-11 px-6 py-2.5 min-w-[100px] border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer flex items-center justify-center shadow-2xs">
                    Batal
                </button>
                <button type="submit" class="h-11 px-7 py-2.5 min-w-[160px] bg-[#1E2538] hover:bg-[#121724] text-white rounded-xl text-sm font-bold transition-all cursor-pointer shadow-xs hover:shadow-md flex items-center justify-center space-x-2">
                    <span>Simpan Perubahan</span>
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

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeDeleteSiswaModal()" class="h-11 px-6 py-2.5 min-w-[100px] border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer flex items-center justify-center shadow-2xs">
                    Batal
                </button>
                <button type="submit" class="h-11 px-7 py-2.5 min-w-[170px] bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-bold transition-all cursor-pointer shadow-sm hover:shadow-md whitespace-nowrap shrink-0 flex items-center justify-center space-x-2">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    <span>Ya, Pindahkan ke Sampah</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL IMPORT DATA SISWA VIA CSV -->
<div id="modalImportSiswa" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white dark:bg-[#151B26] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <span class="font-bold text-slate-900 dark:text-slate-100 text-sm uppercase tracking-tight flex items-center space-x-2">
                <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center">
                    <i data-lucide="file-up" class="w-3.5 h-3.5"></i>
                </div>
                <span>Import Data Siswa (CSV)</span>
            </span>
            <button type="button" onclick="closeImportSiswaModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="p-3 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/60 rounded-xl space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-900 dark:text-emerald-300">Petunjuk Format CSV:</span>
                    <a href="{{ route('admin.siswa.template') }}" class="inline-flex items-center space-x-1 text-[11px] font-bold text-emerald-700 hover:text-emerald-800 dark:text-emerald-400 underline">
                        <i data-lucide="download" class="w-3 h-3"></i>
                        <span>Download Template</span>
                    </a>
                </div>
                <ul class="text-[11px] text-emerald-800 dark:text-emerald-300/90 list-disc list-inside space-y-1">
                    <li>Pemisah kolom: koma (<code>,</code>) atau titik koma (<code>;</code>).</li>
                    <li>Header: <code>nis</code>, <code>nisn</code>, <code>nama_siswa</code>, <code>kelas</code>, <code>jenis_kelamin</code>, <code>no_hp_wali</code>.</li>
                    <li>Password wali murid otomatis: <code>ortu123</code>.</li>
                    <li>Jika NIS sudah ada, data profil & akun akan <strong>di-update</strong>.</li>
                </ul>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Pilih File Excel (.xls) / CSV *</label>
                <input type="file" name="file_csv" accept=".xls, .xlsx, .csv, .txt" required
                    class="w-full text-xs text-slate-700 dark:text-slate-300 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer border border-slate-200 dark:border-slate-700 rounded-xl">
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeImportSiswaModal()" class="h-11 px-6 py-2.5 min-w-[100px] border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer flex items-center justify-center shadow-2xs">
                    Batal
                </button>
                <button type="submit" class="h-11 px-7 py-2.5 min-w-[160px] bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold transition-all cursor-pointer shadow-sm hover:shadow-md flex items-center justify-center space-x-2">
                    <i data-lucide="upload" class="w-4 h-4"></i>
                    <span>Proses Import</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Siswa Baru (Pop-up Default Hidden) -->
<div id="modalTambahSiswa" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 overflow-y-auto">
    <div class="bg-white dark:bg-[#1C2433] rounded-2xl max-w-lg w-full p-5 sm:p-6 shadow-2xl border border-slate-200 dark:border-slate-800 space-y-4 my-auto">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-base">Tambah Siswa Baru</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Isi data lengkap calon siswa & rombel</p>
                </div>
            </div>
            <button type="button" onclick="closeTambahSiswaModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('admin.siswa.store') }}" method="POST" class="space-y-3.5">
            @csrf

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">NIS <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="nis" value="{{ old('nis') }}" placeholder="Contoh: 12345"
                        class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-600 font-mono transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">NISN (Akun Ortu) *</label>
                    <input type="text" name="nisn" value="{{ old('nisn') }}" placeholder="10 Digit NISN" required
                        class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-600 font-mono transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap Siswa *</label>
                <input type="text" name="nama_siswa" value="{{ old('nama_siswa') }}" placeholder="Nama Siswa..." required
                    class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-600 transition-colors">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" required
                        class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 font-medium focus:outline-none focus:border-blue-600 cursor-pointer">
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Rombel / Kelas *</label>
                    <select name="id_kelas" required
                        class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 font-medium focus:outline-none focus:border-blue-600 cursor-pointer">
                        <option value="">-- Pilih --</option>
                        @foreach ($kelasList as $k)
                            <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tempat Lahir</label>
                    <input type="text" name="kota_lahir" value="{{ old('kota_lahir') }}" placeholder="Tulungagung"
                        class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-600 transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                        class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:border-blue-600 transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">No. WA Wali Siswa</label>
                <input type="text" name="no_hp_wali" value="{{ old('no_hp_wali') }}" placeholder="08xxxxxxxxxx"
                    class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-blue-600 font-mono transition-colors">
            </div>

            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeTambahSiswaModal()" class="h-10 px-4 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-10 px-5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-colors shadow-xs flex items-center space-x-1.5 cursor-pointer">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Simpan Siswa</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openTambahSiswaModal() {
        document.getElementById('modalTambahSiswa').classList.remove('hidden');
    }

    function closeTambahSiswaModal() {
        document.getElementById('modalTambahSiswa').classList.add('hidden');
    }

    function openImportSiswaModal() {
        document.getElementById('modalImportSiswa').classList.remove('hidden');
    }

    function closeImportSiswaModal() {
        document.getElementById('modalImportSiswa').classList.add('hidden');
    }

    function openEditModal(targetKey, nama, nisn, idKelas, jk, noHpWali, nis, kotaLahir = '', tglLahir = '') {
        document.getElementById('edit_nis').value = nis || '';
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_nisn').value = nisn;
        document.getElementById('edit_kelas').value = idKelas;
        document.getElementById('edit_jk').value = jk;
        document.getElementById('edit_no_hp_wali').value = noHpWali;
        document.getElementById('edit_kota_lahir').value = kotaLahir || '';
        document.getElementById('edit_tanggal_lahir').value = tglLahir || '';

        document.getElementById('formEditSiswa').action = `/admin/siswa/${targetKey}`;
        document.getElementById('modalEditSiswa').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modalEditSiswa').classList.add('hidden');
    }

    function openDeleteSiswaModal(targetKey, nama) {
        document.getElementById('deleteSiswaNamaTarget').innerText = `Siswa: ${nama} (NISN: ${targetKey})`;
        document.getElementById('formDeleteSiswa').action = `/admin/siswa/${targetKey}`;
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

        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                clearTimeout(ajaxTimer);
                performAjaxSearch();
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                // Fast AJAX query (150ms debounce) searching ALL students without page reloads
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