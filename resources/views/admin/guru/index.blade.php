@extends('layouts.app')

@section('title', 'Data Guru & Pegawai - Jurnal Esemkita')

@section('content')
<div class="flex-1 flex flex-col min-h-0 space-y-2.5">
    <!-- Header Halaman (Proporsi Gambar 1 - FIXED) -->
    <div class="shrink-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div class="flex items-center space-x-2.5">
            <h1 class="text-lg font-bold text-slate-900 tracking-tight">Data Guru & Pegawai</h1>
            <span class="px-2 py-0.5 text-[11px] font-semibold bg-slate-100 text-slate-700 rounded-md font-mono tabular-nums border border-slate-200">
                {{ $totalGuru }} Pendidik
            </span>
        </div>
        <div class="flex items-center space-x-1.5">
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="h-8 px-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer">
                <i data-lucide="panel-left-close" class="w-3.5 h-3.5 text-slate-500"></i>
                <span id="btnToggleFormText">Sembunyikan Form Tambah</span>
            </button>
            <button type="button" onclick="openImportGuruModal()"
                class="h-8 px-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer">
                <i data-lucide="file-up" class="w-3.5 h-3.5"></i>
                <span>Import CSV</span>
            </button>
            <a href="{{ route('admin.guru.trash') }}" class="h-8 px-3 border border-rose-200 bg-rose-50/80 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                <span>Sampah</span>
            </a>
        </div>
    </div>

    <!-- Layout Grid Utama (Full Viewport Height - Proporsi Gambar 1) -->
    <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-3 gap-3.5 items-stretch" id="masterDataGrid">
        
        <!-- BAGIAN 1: FORM TAMBAH GURU (UKURAN KOMPAK & RAMPING SESUAI GAMBAR 1) -->
        <div id="formCol" class="bg-white border border-slate-200/90 rounded-xl p-3.5 shadow-2xs space-y-2.5 overflow-y-auto h-full max-h-full">
            <div class="flex items-center space-x-2 pb-2 border-b border-slate-100 shrink-0">
                <div class="w-5 h-5 rounded-md bg-[#1E2538] text-white flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-3 h-3"></i>
                </div>
                <span class="font-bold text-xs text-slate-900 tracking-tight uppercase">TAMBAH PEGAWAI BARU</span>
            </div>

            <form action="{{ route('admin.guru.store') }}" method="POST" class="space-y-2">
                @csrf

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">NIP / Kode Pegawai *</label>
                    <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Contoh: 198001012005011001" required
                        class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 font-mono transition-colors">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Nama Lengkap & Gelar *</label>
                    <input type="text" name="nama_guru" value="{{ old('nama_guru') }}" placeholder="Contoh: Drs. Budi Santoso, M.Pd" required
                        class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Nomor HP (WhatsApp)</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890"
                        class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 font-mono transition-colors">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Role / Jabatan Sistem *</label>
                    <select name="role" required
                        class="w-full h-9 px-3 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                        <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru Mata Pelajaran</option>
                        <option value="guru_piket" {{ old('role') == 'guru_piket' ? 'selected' : '' }}>Guru Piket</option>
                        @if(isset($existingKepsek) && $existingKepsek)
                            <option value="kepala_sekolah" disabled class="bg-slate-100 text-slate-400">Kepala Sekolah (Sudah Ada: {{ $existingKepsek->nama_display }})</option>
                        @else
                            <option value="kepala_sekolah" {{ old('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                        @endif
                        <option value="wakasis_siswa" {{ old('role') == 'wakasis_siswa' ? 'selected' : '' }}>Wakil Kesiswaan (Siswa)</option>
                        <option value="wakasis_guru" {{ old('role') == 'wakasis_guru' ? 'selected' : '' }}>Wakil Kesiswaan (Guru / SDM)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Password Akun Login *</label>
                    <div class="relative">
                        <input type="password" name="password" id="password_tambah_guru" placeholder="Minimal 4 karakter" required
                            class="w-full h-8 pl-2.5 pr-8 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                        <button type="button" onclick="togglePasswordVisibility('password_tambah_guru', 'eye_tambah_guru')" 
                            class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-700 cursor-pointer" title="Lihat/Sembunyikan Password">
                            <i data-lucide="eye" id="eye_tambah_guru" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full min-h-[48px] py-3.5 px-6 bg-[#1E2538] hover:bg-[#121724] text-white rounded-xl text-sm font-bold tracking-wide transition-all flex items-center justify-center space-x-2.5 shadow-sm hover:shadow-md cursor-pointer shrink-0">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Simpan Pegawai</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: DATA TABLE CARD (PROPORSI & DENSITAS SESUAI GAMBAR 1) -->
        <div id="tableCol" class="lg:col-span-2 flex flex-col h-full min-h-0">
            <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden flex flex-col h-full min-h-0">
                <!-- Top Control Bar (Search + Jabatan) -->
                <div class="shrink-0 px-3.5 py-2 border-b border-slate-200 bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <form action="{{ route('admin.guru.index') }}" method="GET" class="flex flex-1 flex-col sm:flex-row items-center gap-2 w-full">
                        <div class="relative flex-1 w-full">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="search" id="liveSearchGuru" value="{{ request('search') }}" placeholder="Cari Nama, NIP, atau Jabatan..." 
                                class="w-full h-8 pl-8 pr-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                        </div>

                        <div class="w-full sm:w-40">
                            <select name="jabatan" onchange="this.form.submit()" class="w-full h-9 px-3 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                                <option value="">Semua Jabatan</option>
                                <option value="Guru" {{ request('jabatan') == 'Guru' ? 'selected' : '' }}>Guru Mapel</option>
                                <option value="Guru Piket" {{ request('jabatan') == 'Guru Piket' ? 'selected' : '' }}>Guru Piket</option>
                                <option value="Kepala Sekolah" {{ request('jabatan') == 'Kepala Sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                <option value="Wakasis Siswa" {{ request('jabatan') == 'Wakasis Siswa' ? 'selected' : '' }}>Wakasis Siswa</option>
                                <option value="Wakasis Guru" {{ request('jabatan') == 'Wakasis Guru' ? 'selected' : '' }}>Wakasis Guru</option>
                            </select>
                        </div>

                        @if (request('search') || request('jabatan'))
                            <a href="{{ route('admin.guru.index') }}" class="h-8 px-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-semibold flex items-center transition-colors shrink-0">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Table Body (INTERNAL SCROLL DENGAN DENSITAS KOMPAK SESUAI GAMBAR 1) -->
                <div class="flex-1 overflow-y-auto overflow-x-auto min-h-0">
                    <table class="w-full text-left border-collapse text-xs" id="tableGuru">
                        <thead class="sticky top-0 bg-white border-b border-slate-200 z-10 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="py-2.5 px-3.5 text-center w-12 bg-white">NO</th>
                                <th class="py-2.5 px-3.5 bg-white">NAMA LENGKAP & NIP</th>
                                <th class="py-2.5 px-3.5 w-40 bg-white">JABATAN / ROLE</th>
                                <th class="py-2.5 px-3.5 w-36 bg-white">NO. HP (WHATSAPP)</th>
                                <th class="py-2.5 px-3.5 text-center w-20 bg-white">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700" id="guruTbody">
                            @include('admin.guru.partials.rows', ['guruList' => $guruList])
                        </tbody>
                    </table>
                </div>

                <!-- Bottom Pagination Bar (Sesuai Gambar 1 & Gambar 2) -->
                <div class="shrink-0" id="paginationContainer">
                    <x-pagination-bar :paginator="$guruList" />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT DATA GURU (Proporsional & Lebih Lega Sesuai Permintaan) -->
<div id="modalEditGuru" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white dark:bg-[#151B26] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl max-w-lg w-full p-6 sm:p-7 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <span class="font-bold text-slate-900 dark:text-slate-100 text-sm uppercase tracking-tight flex items-center space-x-2">
                <div class="w-7 h-7 rounded-lg bg-[#1E2538] text-white flex items-center justify-center">
                    <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                </div>
                <span>Edit Data Pegawai</span>
            </span>
            <button type="button" onclick="closeEditGuruModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="formEditGuru" method="POST" class="space-y-3.5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">NIP / Kode Pegawai *</label>
                <input type="text" name="nip" id="edit_nip" required
                    class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 font-mono focus:outline-none focus:border-slate-800 transition-colors">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap & Gelar *</label>
                <input type="text" name="nama_guru" id="edit_nama_guru" required
                    class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-slate-800 transition-colors">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor HP (WhatsApp)</label>
                <input type="text" name="no_hp" id="edit_no_hp" placeholder="Contoh: 081234567890"
                    class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 font-mono focus:outline-none focus:border-slate-800 transition-colors">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Role / Jabatan Sistem *</label>
                <select name="role" id="edit_role" required
                    class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                    <option value="guru">Guru Mata Pelajaran</option>
                    <option value="guru_piket">Guru Piket</option>
                    <option value="kepala_sekolah">Kepala Sekolah</option>
                    <option value="wakasis_siswa">Wakil Kesiswaan (Siswa)</option>
                    <option value="wakasis_guru">Wakil Kesiswaan (Guru / SDM)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Ganti Password (Kosongkan jika tidak diubah)</label>
                <div class="relative">
                    <input type="password" name="password" id="edit_password_guru" placeholder="Masukkan password baru..."
                        class="w-full h-10 pl-3 pr-9 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 focus:outline-none focus:border-slate-800 transition-colors">
                    <button type="button" onclick="togglePasswordVisibility('edit_password_guru', 'eye_edit_guru')" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 cursor-pointer" title="Lihat/Sembunyikan Password">
                        <i data-lucide="eye" id="eye_edit_guru" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeEditGuruModal()" class="h-11 px-6 py-2.5 min-w-[100px] border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer flex items-center justify-center shadow-2xs">
                    Batal
                </button>
                <button type="submit" class="h-11 px-7 py-2.5 min-w-[160px] bg-[#1E2538] hover:bg-[#121724] text-white rounded-xl text-sm font-bold transition-all cursor-pointer shadow-xs hover:shadow-md flex items-center justify-center space-x-2">
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL KONFIRMASI HAPUS GURU DENGAN ALASAN -->
<div id="modalDeleteGuru" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white dark:bg-[#151B26] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 flex items-center justify-center shrink-0">
                <i data-lucide="trash-2" class="w-5 h-5 text-rose-600 dark:text-rose-400"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 dark:text-slate-100 text-sm tracking-tight">Pindahkan Guru ke Sampah</h3>
                <p class="text-[11.5px] text-slate-500 dark:text-slate-400 font-medium mt-0.5" id="deleteGuruNamaTarget"></p>
            </div>
        </div>

        <form id="formDeleteGuru" method="POST" class="space-y-3.5 pt-1">
            @csrf
            @method('DELETE')

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Alasan Penghapusan / Dipindahkan *</label>
                <div class="space-y-2 text-xs">
                    <label class="flex items-center space-x-3 px-3 py-2.5 bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/70 rounded-xl cursor-pointer hover:border-slate-300 dark:hover:border-slate-600 transition-colors">
                        <input type="radio" name="alasan_preset_guru" value="Pensiun / Purna Tugas" onchange="setAlasanGuru(this.value)" checked class="w-4 h-4 accent-rose-600 cursor-pointer">
                        <span class="font-semibold text-slate-800 dark:text-slate-200">Pensiun / Purna Tugas</span>
                    </label>
                    <label class="flex items-center space-x-3 px-3 py-2.5 bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/70 rounded-xl cursor-pointer hover:border-slate-300 dark:hover:border-slate-600 transition-colors">
                        <input type="radio" name="alasan_preset_guru" value="Mengundurkan Diri / Resign" onchange="setAlasanGuru(this.value)" class="w-4 h-4 accent-rose-600 cursor-pointer">
                        <span class="font-semibold text-slate-800 dark:text-slate-200">Mengundurkan Diri / Resign</span>
                    </label>
                    <label class="flex items-center space-x-3 px-3 py-2.5 bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/70 rounded-xl cursor-pointer hover:border-slate-300 dark:hover:border-slate-600 transition-colors">
                        <input type="radio" name="alasan_preset_guru" value="Mutasi / Pindah Instansi" onchange="setAlasanGuru(this.value)" class="w-4 h-4 accent-rose-600 cursor-pointer">
                        <span class="font-semibold text-slate-800 dark:text-slate-200">Mutasi / Pindah Instansi</span>
                    </label>
                    <label class="flex items-center space-x-3 px-3 py-2.5 bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/70 rounded-xl cursor-pointer hover:border-slate-300 dark:hover:border-slate-600 transition-colors">
                        <input type="radio" name="alasan_preset_guru" value="" onchange="setAlasanGuru('')" class="w-4 h-4 accent-rose-600 cursor-pointer">
                        <span class="font-semibold text-slate-800 dark:text-slate-200">Lainnya (Ketik Manual...)</span>
                    </label>
                </div>
            </div>

            <div>
                <input type="text" name="alasan_hapus" id="inputAlasanGuru" value="Pensiun / Purna Tugas" placeholder="Tuliskan alasan detail..." required
                    class="w-full h-10 px-3.5 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:border-rose-500 transition-colors shadow-2xs">
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeDeleteGuruModal()" class="h-11 px-6 py-2.5 min-w-[100px] border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer flex items-center justify-center shadow-2xs">
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

<!-- MODAL IMPORT DATA GURU VIA CSV -->
<div id="modalImportGuru" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white dark:bg-[#151B26] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <span class="font-bold text-slate-900 dark:text-slate-100 text-sm uppercase tracking-tight flex items-center space-x-2">
                <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center">
                    <i data-lucide="file-up" class="w-3.5 h-3.5"></i>
                </div>
                <span>Import Data Guru (CSV)</span>
            </span>
            <button type="button" onclick="closeImportGuruModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('admin.guru.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="p-3 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/60 rounded-xl space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-emerald-900 dark:text-emerald-300">Petunjuk Format CSV:</span>
                    <a href="{{ route('admin.guru.template') }}" class="inline-flex items-center space-x-1 text-[11px] font-bold text-emerald-700 hover:text-emerald-800 dark:text-emerald-400 underline">
                        <i data-lucide="download" class="w-3 h-3"></i>
                        <span>Download Template</span>
                    </a>
                </div>
                <ul class="text-[11px] text-emerald-800 dark:text-emerald-300/90 list-disc list-inside space-y-1">
                    <li>Pemisah kolom: koma (<code>,</code>) atau titik koma (<code>;</code>).</li>
                    <li>Header: <code>nip</code>, <code>nama_guru</code>, <code>no_hp</code>, <code>role</code>, <code>password</code>.</li>
                    <li>Password default: <code>guru123</code> jika dikosongkan.</li>
                    <li>Jika NIP sudah ada, data profil & akun akan <strong>di-update</strong>.</li>
                </ul>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Pilih File Excel (.xls) / CSV *</label>
                <input type="file" name="file_csv" accept=".xls, .xlsx, .csv, .txt" required
                    class="w-full text-xs text-slate-700 dark:text-slate-300 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer border border-slate-200 dark:border-slate-700 rounded-xl">
            </div>

            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeImportGuruModal()" class="h-10 px-4 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 transition-colors">
                    Batal
                </button>
                <button type="submit" class="h-10 px-5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-colors shadow-xs flex items-center space-x-1.5">
                    <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                    <span>Proses Import</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openImportGuruModal() {
        document.getElementById('modalImportGuru').classList.remove('hidden');
    }

    function closeImportGuruModal() {
        document.getElementById('modalImportGuru').classList.add('hidden');
    }

    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input) return;
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) {
                icon.setAttribute('data-lucide', 'eye-off');
                if (window.lucide) window.lucide.createIcons();
            }
        } else {
            input.type = 'password';
            if (icon) {
                icon.setAttribute('data-lucide', 'eye');
                if (window.lucide) window.lucide.createIcons();
            }
        }
    }

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

    function openEditGuruModal(id, nama, nip, noHp, role) {
        document.getElementById('edit_nama_guru').value = nama;
        document.getElementById('edit_nip').value = nip;
        document.getElementById('edit_no_hp').value = noHp;
        const roleSelect = document.getElementById('edit_role');
        if (roleSelect) {
            roleSelect.value = role;
            if (roleSelect.tomselect) {
                roleSelect.tomselect.setValue(role, true);
            }
        }

        document.getElementById('formEditGuru').action = `/admin/guru/${id}`;
        document.getElementById('modalEditGuru').classList.remove('hidden');
    }

    function closeEditGuruModal() {
        document.getElementById('modalEditGuru').classList.add('hidden');
    }

    function openDeleteGuruModal(id, nama) {
        document.getElementById('deleteGuruNamaTarget').innerText = `Guru: ${nama}`;
        document.getElementById('formDeleteGuru').action = `/admin/guru/${id}`;
        document.getElementById('inputAlasanGuru').value = 'Pensiun / Purna Tugas';
        document.getElementById('modalDeleteGuru').classList.remove('hidden');
    }

    function closeDeleteGuruModal() {
        document.getElementById('modalDeleteGuru').classList.add('hidden');
    }

    function setAlasanGuru(val) {
        const input = document.getElementById('inputAlasanGuru');
        if (val) {
            input.value = val;
        } else {
            input.value = '';
            input.focus();
        }
    }

    // Dynamic AJAX Live Search Across All Teachers (Zero Page Reloads)
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('liveSearchGuru');
        const searchForm = searchInput ? searchInput.closest('form') : null;
        let ajaxTimer = null;

        function performAjaxSearch() {
            if (!searchInput) return;
            const q = searchInput.value.trim();
            const form = searchForm || searchInput.form;

            const formData = new FormData(form);
            formData.set('search', q);
            const params = new URLSearchParams(formData);

            fetch(`{{ route('admin.guru.index') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('guruTbody');
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
                clearTimeout(ajaxTimer);
                ajaxTimer = setTimeout(() => {
                    performAjaxSearch();
                }, 200);
            });

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
