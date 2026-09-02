@extends('layouts.app')

@section('title', 'Master Data Siswa - Jurnal Esemkita')

@section('content')
<div class="space-y-6">
    <!-- Header Halaman -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Master Data Siswa</h1>
            <p class="text-xs text-gray-500 mt-0.5">Kelola data seluruh peserta didik, kelas, dan akun akses wali murid</p>
        </div>
        <div class="flex items-center space-x-3">
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="px-4 py-2 bg-white border border-[#D1D9EB] hover:bg-gray-50 text-[#1E2538] rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer">
                <i data-lucide="layout-grid" class="w-4 h-4 text-[#405078]"></i>
                <span id="btnToggleFormText">Sembunyikan Form Tambah</span>
            </button>
            <a href="{{ route('admin.siswa.trash') }}" class="px-4 py-2 border border-rose-200 bg-rose-50/50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
                <span>Sampah Siswa</span>
            </a>
        </div>
    </div>

    <!-- NOTIFIKASI -->
    @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center space-x-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-semibold">
            <ul class="list-disc pl-4 space-y-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start" id="masterDataGrid">
        <!-- BAGIAN 1: FORM PENAMBAHAN SISWA BARU (DAPAT DI-FOLD / DILIPAT) -->
        <div id="formCol" class="lg:col-span-1 bg-white border border-[#D1D9EB] rounded-2xl p-5 shadow-sm space-y-4 transition-all duration-300">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center space-x-2">
                    <i data-lucide="user-plus" class="w-4 h-4 text-[#405078]"></i>
                    <h3 class="font-bold text-[#1E2538] text-sm">Tambah Siswa Baru</h3>
                </div>
            </div>

            <form action="{{ route('admin.siswa.store') }}" method="POST" class="space-y-3.5">
                @csrf

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Nomor Induk Siswa (NIS) *</label>
                    <input type="text" name="nis" value="{{ old('nis') }}" placeholder="Contoh: 24435" required
                        class="block w-full px-3.5 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">NISN *</label>
                    <input type="text" name="nisn" value="{{ old('nisn') }}" placeholder="10 Digit NISN Nasional" required
                        class="block w-full px-3.5 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Lengkap Siswa *</label>
                    <input type="text" name="nama_siswa" value="{{ old('nama_siswa') }}" placeholder="Nama sesuai akta" required
                        class="block w-full px-3.5 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Jenis Kelamin *</label>
                        <select name="jenis_kelamin" required
                            class="block w-full px-3 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078] cursor-pointer">
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>L (Laki-laki)</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>P (Perempuan)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Kelas *</label>
                        <select name="id_kelas" required
                            class="block w-full px-3 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078] cursor-pointer">
                            <option value="">Pilih Kelas</option>
                            @foreach ($kelasList as $k)
                                <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">No. WhatsApp / HP Wali Murid</label>
                    <input type="text" name="no_hp_wali" value="{{ old('no_hp_wali') }}" placeholder="Contoh: 08123456789"
                        class="block w-full px-3.5 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold transition-colors shadow-sm flex items-center justify-center space-x-1.5 cursor-pointer">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>Simpan Data Siswa</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR & LIVE FILTER SISWA (DAPAT MELEBAR JIKA FORM DILIPAT) -->
        <div id="tableCol" class="lg:col-span-2 space-y-4">
            
            <!-- STICKY FILTER CARD (DENGAN LIVE INSTANT SEARCH) -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-4 sticky top-6 z-20">
                <form action="{{ route('admin.siswa.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Pencarian Cepat (Ketik Langsung Tersaring)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i data-lucide="search" class="w-4 h-4 text-[#8697C3]"></i>
                            </div>
                            <input type="text" name="search" id="liveSearchInput" value="{{ request('search') }}" placeholder="Ketik Nama Siswa, NIS, atau NISN..." 
                                class="block w-full pl-9 pr-4 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                        </div>
                    </div>

                    <div class="w-full sm:w-44">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Filter Kelas</label>
                        <select name="id_kelas" onchange="this.form.submit()" class="block w-full px-3 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] font-medium focus:outline-none focus:border-[#405078] cursor-pointer">
                            <option value="">Semua Kelas ({{ $totalSiswa }})</option>
                            @foreach ($kelasList as $k)
                                <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if (request('search') || request('id_kelas'))
                        <a href="{{ route('admin.siswa.index') }}" class="px-3 py-2 border border-[#D1D9EB] hover:bg-gray-50 text-gray-600 rounded-xl text-xs font-semibold flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- TABLE CARD (FIXED SCROLL CONTAINER DENGAN STICKY HEADER) -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden flex flex-col">
                <div class="overflow-x-auto max-h-[580px] overflow-y-auto">
                    <table class="w-full text-left border-collapse" id="tableSiswa">
                        <thead class="sticky top-0 bg-[#F8FAFC] z-10">
                            <tr class="text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                                <th class="py-3.5 px-4 text-center w-12">No</th>
                                <th class="py-3.5 px-4 w-28">NIS</th>
                                <th class="py-3.5 px-4 w-32">NISN</th>
                                <th class="py-3.5 px-4">Nama Siswa</th>
                                <th class="py-3.5 px-3 text-center w-14">L/P</th>
                                <th class="py-3.5 px-4 w-32">Kelas</th>
                                <th class="py-3.5 px-4 w-36">No. HP Wali</th>
                                <th class="py-3.5 px-4 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600" id="siswaTbody">
                            @forelse ($siswaList as $idx => $s)
                                <tr class="hover:bg-gray-50/50 transition-colors siswa-row" data-search="{{ strtolower($s->nama_siswa . ' ' . $s->nis . ' ' . $s->nisn . ' ' . ($s->kelas ? $s->kelas->nama_kelas : '')) }}">
                                    <td class="py-3 px-4 text-center font-medium text-gray-400 text-xs">
                                        {{ $siswaList->firstItem() + $idx }}
                                    </td>
                                    <td class="py-3 px-4 font-bold text-[#405078] text-xs">{{ $s->nis }}</td>
                                    <td class="py-3 px-4 text-xs font-semibold text-gray-500">{{ $s->nisn }}</td>
                                    <td class="py-3 px-4 font-bold text-[#1E2538] text-xs">{{ $s->nama_siswa }}</td>
                                    <td class="py-3 px-3 text-center">
                                        <span class="px-2 py-0.5 rounded-md text-[11px] font-bold {{ $s->jenis_kelamin === 'P' ? 'bg-pink-50 text-pink-700' : 'bg-blue-50 text-blue-700' }}">
                                            {{ $s->jenis_kelamin ?? 'L' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#405078]/10 text-[#405078]">
                                            {{ $s->kelas ? $s->kelas->nama_kelas : '-' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-xs text-gray-500">
                                        {{ $s->no_hp_wali ?? '-' }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center space-x-1.5">
                                            <button type="button" 
                                                onclick="openEditModal('{{ $s->nis }}', '{{ addslashes($s->nama_siswa) }}', '{{ $s->nisn }}', '{{ $s->id_kelas }}', '{{ $s->jenis_kelamin ?? 'L' }}', '{{ $s->no_hp_wali ?? '' }}')"
                                                class="p-1.5 text-[#405078] hover:bg-[#405078]/10 rounded-lg transition-colors cursor-pointer" title="Edit Data Siswa">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </button>
                                            <form action="{{ route('admin.siswa.destroy', $s->nis) }}" method="POST" onsubmit="return confirm('Pindahkan siswa {{ addslashes($s->nama_siswa) }} ke sampah?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus Siswa">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-gray-400 italic text-xs">
                                        Tidak ada data siswa yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION BAR (SESUAI GAMBAR FIGMA 1) -->
                <x-pagination-bar :paginator="$siswaList" />
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT DATA SISWA -->
<div id="modalEditSiswa" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl border border-[#D1D9EB] shadow-2xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 class="font-bold text-[#1E2538] text-base">Edit Profil Siswa</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="formEditSiswa" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Nomor Induk Siswa (NIS)</label>
                <input type="text" id="edit_nis" disabled
                    class="block w-full px-3.5 py-2 bg-gray-100 border border-gray-200 rounded-xl text-xs text-gray-500 font-bold">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">NISN *</label>
                <input type="text" name="nisn" id="edit_nisn" required
                    class="block w-full px-3.5 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078]">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Lengkap Siswa *</label>
                <input type="text" name="nama_siswa" id="edit_nama" required
                    class="block w-full px-3.5 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078]">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" id="edit_jk" required
                        class="block w-full px-3 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078]">
                        <option value="L">L (Laki-laki)</option>
                        <option value="P">P (Perempuan)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Kelas *</label>
                    <select name="id_kelas" id="edit_kelas" required
                        class="block w-full px-3 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078]">
                        @foreach ($kelasList as $k)
                            <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">No. WhatsApp / HP Wali Murid</label>
                <input type="text" name="no_hp_wali" id="edit_no_hp_wali" placeholder="08xxxxxxxxxx"
                    class="block w-full px-3.5 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078]">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-[#D1D9EB] rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold shadow-sm cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fitur Fold / Sembunyikan Form Tambah
    let isFormFolded = false;
    function toggleFormPanel() {
        const formCol = document.getElementById('formCol');
        const tableCol = document.getElementById('tableCol');
        const btnText = document.getElementById('btnToggleFormText');

        if (!isFormFolded) {
            formCol.style.display = 'none';
            tableCol.className = 'lg:col-span-3 space-y-4';
            btnText.innerText = 'Buka Form Tambah';
            isFormFolded = true;
        } else {
            formCol.style.display = 'block';
            tableCol.className = 'lg:col-span-2 space-y-4';
            btnText.innerText = 'Sembunyikan Form Tambah';
            isFormFolded = false;
        }
    }

    // Modal Edit Siswa
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

    // Live Client-Side Instant Search
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