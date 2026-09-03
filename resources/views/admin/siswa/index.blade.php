@extends('layouts.app')

@section('title', 'Master Data Siswa - Jurnal Esemkita')

@section('content')
<div class="space-y-4">
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center space-x-2">
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Master Data Siswa</h1>
                <span class="px-2.5 py-0.5 text-xs font-semibold bg-slate-200 text-slate-700 rounded-full">
                    {{ number_format($totalSiswa, 0, ',', '.') }} Terdaftar
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Kelola direktori siswa, rombongan belajar, dan sinkronisasi wali murid</p>
        </div>
        <div class="flex items-center space-x-2">
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="px-3.5 py-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5 text-slate-600"></i>
                <span id="btnToggleFormText">Sembunyikan Form Tambah</span>
            </button>
            <a href="{{ route('admin.siswa.trash') }}" class="px-3.5 py-1.5 border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                <span>Sampah</span>
            </a>
        </div>
    </div>

    <!-- Alert / Notifikasi -->
    @if (session('success'))
        <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-xs font-semibold flex items-center space-x-2 shadow-2xs">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg text-xs font-semibold shadow-2xs">
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start" id="masterDataGrid">
        <!-- BAGIAN 1: FORM TAMBAH SISWA BARU (STICKY & FOLDABLE) -->
        <div id="formCol" class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-3.5 lg:sticky lg:top-18 z-10">
            <div class="flex items-center space-x-2 pb-2.5 border-b border-slate-100">
                <div class="w-6 h-6 rounded bg-[#1E293B] text-white flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Tambah Siswa Baru</h3>
            </div>

            <form action="{{ route('admin.siswa.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Induk Siswa (NIS) *</label>
                    <input type="text" name="nis" value="{{ old('nis') }}" placeholder="Contoh: 24435" required
                        class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B] focus:ring-1 focus:ring-[#1E293B]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">NISN *</label>
                    <input type="text" name="nisn" value="{{ old('nisn') }}" placeholder="10 Digit NISN Nasional" required
                        class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B] focus:ring-1 focus:ring-[#1E293B]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap Siswa *</label>
                    <input type="text" name="nama_siswa" value="{{ old('nama_siswa') }}" placeholder="Nama sesuai akta lahir" required
                        class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B] focus:ring-1 focus:ring-[#1E293B]">
                </div>

                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Kelamin *</label>
                        <select name="jenis_kelamin" required
                            class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-[#1E293B] cursor-pointer">
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kelas *</label>
                        <select name="id_kelas" required
                            class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-[#1E293B] cursor-pointer">
                            <option value="">Pilih Kelas</option>
                            @foreach ($kelasList as $k)
                                <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">No. WhatsApp / HP Wali Murid</label>
                    <input type="text" name="no_hp_wali" value="{{ old('no_hp_wali') }}" placeholder="Contoh: 08123456789"
                        class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B]">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 px-4 bg-[#1E293B] hover:bg-[#0F172A] text-white rounded-lg text-xs font-bold transition-all shadow-xs flex items-center justify-center space-x-1.5 cursor-pointer">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        <span>Simpan Data Siswa</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR & LIVE FILTER SISWA -->
        <div id="tableCol" class="lg:col-span-2 space-y-3.5">
            <!-- DATA TABLE CARD (SESUAI GAMBAR 5 QS RANKINGS) -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden flex flex-col">
                <!-- Top Control Bar (Search, Filter, Counter) -->
                <div class="p-3.5 border-b border-slate-200 bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <form action="{{ route('admin.siswa.index') }}" method="GET" class="flex flex-1 flex-col sm:flex-row items-center gap-2.5">
                        <div class="relative flex-1 w-full">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="search" id="liveSearchInput" value="{{ request('search') }}" placeholder="Cari Nama Siswa, NIS, atau NISN..." 
                                class="block w-full pl-9 pr-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B] focus:ring-1 focus:ring-[#1E293B] transition-all">
                        </div>

                        <div class="w-full sm:w-44">
                            <select name="id_kelas" onchange="this.form.submit()" class="block w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 font-medium focus:outline-none focus:border-[#1E293B] cursor-pointer">
                                <option value="">Semua Kelas</option>
                                @foreach ($kelasList as $k)
                                    <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if (request('search') || request('id_kelas'))
                            <a href="{{ route('admin.siswa.index') }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-semibold transition-colors">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Scrollable Table Body -->
                <div class="overflow-x-auto max-h-[calc(100vh-270px)] min-h-[400px] overflow-y-auto">
                    <table class="w-full text-left border-collapse text-xs" id="tableSiswa">
                        <thead class="sticky top-0 bg-slate-50 border-b border-slate-200 z-10">
                            <tr class="text-slate-600 font-bold uppercase tracking-wider text-[11px]">
                                <th class="py-3 px-3.5 text-center w-12">No</th>
                                <th class="py-3 px-3.5 w-24">NIS</th>
                                <th class="py-3 px-3.5 w-28">NISN</th>
                                <th class="py-3 px-3.5">Nama Siswa</th>
                                <th class="py-3 px-3 text-center w-14">L/P</th>
                                <th class="py-3 px-3.5 w-28">Kelas</th>
                                <th class="py-3 px-3.5 w-32">No. HP Wali</th>
                                <th class="py-3 px-3.5 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700" id="siswaTbody">
                            @forelse ($siswaList as $idx => $s)
                                <tr class="hover:bg-slate-50/80 transition-colors siswa-row" data-search="{{ strtolower($s->nama_siswa . ' ' . $s->nis . ' ' . $s->nisn . ' ' . ($s->kelas ? $s->kelas->nama_kelas : '')) }}">
                                    <td class="py-2.5 px-3.5 text-center font-medium text-slate-400 text-xs tabular-nums">
                                        {{ $siswaList->firstItem() + $idx }}
                                    </td>
                                    <td class="py-2.5 px-3.5 font-bold text-slate-900 text-xs font-mono tabular-nums">{{ $s->nis }}</td>
                                    <td class="py-2.5 px-3.5 text-xs font-medium text-slate-500 font-mono tabular-nums">{{ $s->nisn }}</td>
                                    <td class="py-2.5 px-3.5 font-semibold text-slate-900 text-xs leading-tight">{{ $s->nama_siswa }}</td>
                                    <td class="py-2.5 px-3 text-center">
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold {{ $s->jenis_kelamin === 'P' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                            {{ $s->jenis_kelamin ?? 'L' }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3.5">
                                        <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $s->kelas ? $s->kelas->nama_kelas : '-' }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3.5 text-xs text-slate-500 font-mono tabular-nums">
                                        {{ $s->no_hp_wali ?: '-' }}
                                    </td>
                                    <td class="py-2.5 px-3.5 text-center">
                                        <div class="flex items-center justify-center space-x-1.5">
                                            <button type="button" 
                                                onclick="openEditModal('{{ $s->nis }}', '{{ addslashes($s->nama_siswa) }}', '{{ $s->nisn }}', '{{ $s->id_kelas }}', '{{ $s->jenis_kelamin ?? 'L' }}', '{{ $s->no_hp_wali ?? '' }}')"
                                                class="w-7 h-7 rounded border border-slate-200 hover:border-slate-300 hover:bg-slate-100 text-slate-600 hover:text-slate-900 flex items-center justify-center transition-colors cursor-pointer" title="Edit Data Siswa">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                            </button>
                                            <form action="{{ route('admin.siswa.destroy', $s->nis) }}" method="POST" onsubmit="return confirm('Pindahkan siswa {{ addslashes($s->nama_siswa) }} ke sampah?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-7 h-7 rounded border border-slate-200 hover:border-rose-300 hover:bg-rose-50 text-slate-600 hover:text-rose-600 flex items-center justify-center transition-colors cursor-pointer" title="Hapus Siswa">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-slate-400 italic text-xs">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                        Tidak ada data siswa yang sesuai pencarian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION BAR (SESUAI GAMBAR 5) -->
                <x-pagination-bar :paginator="$siswaList" />
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT DATA SISWA -->
<div id="modalEditSiswa" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white border border-slate-200 rounded-xl shadow-xl max-w-md w-full p-5 space-y-3.5">
        <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
            <h3 class="font-bold text-slate-900 text-sm flex items-center space-x-1.5">
                <i data-lucide="edit-3" class="w-4 h-4 text-slate-700"></i>
                <span>Edit Profil Siswa</span>
            </h3>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="formEditSiswa" method="POST" class="space-y-3">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Induk Siswa (NIS)</label>
                <input type="text" id="edit_nis" disabled
                    class="block w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-lg text-xs text-slate-500 font-mono font-bold">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">NISN *</label>
                <input type="text" name="nisn" id="edit_nisn" required
                    class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-[#1E293B]">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap Siswa *</label>
                <input type="text" name="nama_siswa" id="edit_nama" required
                    class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-[#1E293B]">
            </div>

            <div class="grid grid-cols-2 gap-2.5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" id="edit_jk" required
                        class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-[#1E293B]">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kelas *</label>
                    <select name="id_kelas" id="edit_kelas" required
                        class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-[#1E293B]">
                        @foreach ($kelasList as $k)
                            <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">No. WhatsApp / HP Wali Murid</label>
                <input type="text" name="no_hp_wali" id="edit_no_hp_wali" placeholder="08xxxxxxxxxx"
                    class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-[#1E293B]">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2.5 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="px-3.5 py-1.5 border border-slate-300 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-4 py-1.5 bg-[#1E293B] hover:bg-[#0F172A] text-white rounded-lg text-xs font-bold transition-all cursor-pointer">
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
            tableCol.className = 'lg:col-span-3 space-y-3.5';
            btnText.innerText = 'Buka Form Tambah';
            isFormFolded = true;
        } else {
            formCol.style.display = 'block';
            tableCol.className = 'lg:col-span-2 space-y-3.5';
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