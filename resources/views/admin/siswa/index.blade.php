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
                            class="searchable-select w-full h-8 px-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
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

                <div class="pt-1">
                    <button type="submit" class="w-full h-8.5 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center space-x-1.5 shadow-xs cursor-pointer">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
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
                            @forelse ($siswaList as $idx => $s)
                                <tr class="hover:bg-slate-50/80 transition-colors siswa-row" data-search="{{ strtolower($s->nama_siswa . ' ' . $s->nis . ' ' . $s->nisn . ' ' . ($s->kelas ? $s->kelas->nama_kelas : '')) }}">
                                    <td class="py-2 px-3 text-center font-medium text-slate-400 text-xs tabular-nums">
                                        {{ $siswaList->firstItem() + $idx }}
                                    </td>
                                    <td class="py-2 px-3 font-semibold text-slate-900 text-xs font-mono tabular-nums">{{ $s->nis }}</td>
                                    <td class="py-2 px-3 text-xs text-slate-500 font-mono tabular-nums">{{ $s->nisn }}</td>
                                    <td class="py-2 px-3 font-medium text-slate-900 text-xs leading-tight">{{ $s->nama_siswa }}</td>
                                    <td class="py-2 px-2 text-center">
                                        @if($s->jenis_kelamin === 'P')
                                            <span class="inline-flex items-center justify-center w-5.5 h-5.5 rounded-md text-[11px] font-bold bg-pink-50 text-pink-700 border border-pink-200" title="Perempuan">P</span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-5.5 h-5.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200" title="Laki-laki">L</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-3">
                                        <span class="inline-block px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $s->kelas ? $s->kelas->nama_kelas : '-' }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-3 text-xs text-slate-500 font-mono tabular-nums">
                                        {{ $s->no_hp_wali ?: '-' }}
                                    </td>
                                    <td class="py-2 px-3 text-center">
                                        <div class="flex items-center justify-center space-x-1">
                                            <button type="button" 
                                                onclick="openEditModal('{{ $s->nis }}', '{{ addslashes($s->nama_siswa) }}', '{{ $s->nisn }}', '{{ $s->id_kelas }}', '{{ $s->jenis_kelamin ?? 'L' }}', '{{ $s->no_hp_wali ?? '' }}')"
                                                class="w-6.5 h-6.5 rounded-md border border-slate-200 hover:border-slate-300 hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Edit Siswa">
                                                <i data-lucide="edit-2" class="w-3 h-3"></i>
                                            </button>
                                            <form action="{{ route('admin.siswa.destroy', $s->nis) }}" method="POST" onsubmit="return confirm('Pindahkan siswa {{ addslashes($s->nama_siswa) }} ke sampah?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-6.5 h-6.5 rounded-md border border-slate-200 hover:border-rose-200 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Hapus Siswa">
                                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-slate-400 italic text-xs">
                                        <i data-lucide="inbox" class="w-7 h-7 mx-auto mb-1.5 text-slate-300"></i>
                                        Tidak ada data siswa yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Bottom Pagination Bar (Sesuai Gambar 1 & Gambar 2) -->
                <div class="shrink-0">
                    <x-pagination-bar :paginator="$siswaList" />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT SISWA -->
<div id="modalEditSiswa" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white border border-slate-200 rounded-xl shadow-xl max-w-md w-full p-4.5 space-y-3">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <span class="font-bold text-slate-900 text-xs uppercase tracking-tight flex items-center space-x-1.5">
                <i data-lucide="edit-2" class="w-3.5 h-3.5 text-slate-700"></i>
                <span>Edit Data Siswa</span>
            </span>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="formEditSiswa" method="POST" class="space-y-2.5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Nomor Induk Siswa (NIS) *</label>
                <input type="text" name="nis" id="edit_nis" required readonly
                    class="w-full h-8 px-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-600 font-mono focus:outline-none cursor-not-allowed">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Nomor Induk Siswa Nasional (NISN) *</label>
                <input type="text" name="nisn" id="edit_nisn" required
                    class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-mono focus:outline-none focus:border-slate-800">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Nama Lengkap Siswa *</label>
                <input type="text" name="nama_siswa" id="edit_nama" required
                    class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-slate-800">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" id="edit_jk" required
                        class="w-full h-8 px-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Rombel / Kelas *</label>
                    <select name="id_kelas" id="edit_kelas" required
                        class="w-full h-8 px-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                        @foreach ($kelasList as $k)
                            <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Nomor WhatsApp Wali Siswa</label>
                <input type="text" name="no_hp_wali" id="edit_no_hp_wali" placeholder="08xxxxxxxxxx"
                    class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-mono focus:outline-none focus:border-slate-800">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="h-8 px-3 border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-8 px-3.5 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg text-xs font-semibold transition-colors cursor-pointer shadow-2xs">
                    Simpan Perubahan
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

    // Live Instant Search
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('liveSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('.siswa-row');
                rows.forEach(row => {
                    const text = row.getAttribute('data-search') || '';
                    if (text.includes(q)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection