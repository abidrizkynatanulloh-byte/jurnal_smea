@extends('layouts.app')

@section('title', 'Kelola Siswa - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Data Siswa</h1>
            <p class="text-xs text-gray-500 mt-1">
                Total terdaftar: <span class="font-semibold text-[#405078]">{{ $totalSiswa }} Siswa Aktif</span>
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="px-4 py-2 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-700 rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-xs cursor-pointer">
                <i data-lucide="panel-left" class="w-4 h-4 text-[#405078]"></i>
                <span id="foldText">Sembunyikan Form Tambah</span>
            </button>
            <a href="{{ route('admin.siswa.trash') }}" class="px-4 py-2 bg-[#405078]/10 hover:bg-[#405078]/20 text-[#405078] rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
                <span>Tong Sampah</span>
            </a>
        </div>
    </div>

    <!-- Responsive Grid: Form on Left, List on Right -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- BAGIAN 1: FORM TAMBAH SISWA BARU (DAPAT DI-FOLD) -->
        <div id="formPanel" class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-6 lg:sticky lg:top-6 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-[#1E2538] text-base flex items-center space-x-2">
                    <i data-lucide="user-plus" class="w-5 h-5 text-[#405078]"></i>
                    <span>Tambah Siswa Baru</span>
                </h3>
            </div>
            
            <form action="{{ route('admin.siswa.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="nis" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">NIS (Nomor Induk Siswa) *</label>
                    <input type="text" name="nis" id="nis" value="{{ old('nis') }}" placeholder="Contoh: 24435" required 
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <div>
                    <label for="nisn" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">NISN (Untuk Akun Wali Murid) *</label>
                    <input type="text" name="nisn" id="nisn" value="{{ old('nisn') }}" placeholder="Contoh: 0081234567" required 
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <div>
                    <label for="nama_siswa" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nama Lengkap Siswa *</label>
                    <input type="text" name="nama_siswa" id="nama_siswa" value="{{ old('nama_siswa') }}" placeholder="Contoh: Muhammad Farhan" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <div>
                    <label for="id_kelas" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Rombongan Belajar (Kelas) *</label>
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

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="jenis_kelamin" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">L/P</label>
                        <select name="jenis_kelamin" id="jenis_kelamin"
                            class="block w-full px-3 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] cursor-pointer">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label for="no_hp_wali" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">No HP Wali</label>
                        <input type="text" name="no_hp_wali" id="no_hp_wali" value="{{ old('no_hp_wali') }}" placeholder="08xxxxxxxx"
                            class="block w-full px-3 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078]">
                    </div>
                </div>

                <button type="submit" class="w-full mt-2 py-3 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center space-x-2 cursor-pointer">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Siswa</span>
                </button>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR & LIVE FILTER SISWA -->
        <div id="tableCol" class="lg:col-span-2 space-y-6">
            
            <!-- STICKY FILTER CARD (DENGAN LIVE INSTANT SEARCH) -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-4 sticky top-6 z-20">
                <form action="{{ route('admin.siswa.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Pencarian Cepat (Ketik Langsung Tersaring)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i data-lucide="search" class="w-4 h-4 text-[#8697C3]"></i>
                            </div>
                            <input type="text" name="search" id="liveSearchSiswa" value="{{ request('search') }}" placeholder="Ketik Nama, NIS, atau NISN Siswa..." 
                                class="block w-full pl-9 pr-4 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                        </div>
                    </div>
                    
                    <div class="w-full sm:w-48">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Filter Kelas</label>
                        <select name="id_kelas" onchange="this.form.submit()"
                            class="block w-full px-3 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                            <option value="">Semua Kelas</option>
                            @foreach ($kelasList as $k)
                                <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
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

            <!-- TABEL DATA SISWA (FIXED SCROLL DENGAN STICKY HEADER) -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto max-h-[580px] overflow-y-auto">
                    <table class="w-full text-left border-collapse" id="tableSiswa">
                        <thead class="sticky top-0 bg-[#F8FAFC] z-10">
                            <tr class="text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                                <th class="py-3 px-4 text-center w-12">No</th>
                                <th class="py-3 px-4 w-28">NIS</th>
                                <th class="py-3 px-4">Nama Siswa</th>
                                <th class="py-3 px-4 w-32">Kelas</th>
                                <th class="py-3 px-4 w-28 text-center">Akun Ortu</th>
                                <th class="py-3 px-4 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse ($siswaList as $index => $s)
                                <tr class="hover:bg-gray-50/50 transition-colors siswa-row" data-search="{{ strtolower($s->nama_siswa . ' ' . $s->nis . ' ' . $s->nisn . ' ' . ($s->kelas ? $s->kelas->nama_kelas : '')) }}">
                                    <td class="py-3.5 px-4 text-center font-medium text-gray-400 text-xs">{{ $siswaList->firstItem() + $index }}</td>
                                    <td class="py-3.5 px-4 font-semibold text-[#1E2538] text-xs">{{ $s->nis }}</td>
                                    <td class="py-3.5 px-4 font-bold text-[#1E2538]">
                                        {{ $s->nama_siswa }}
                                        <span class="block text-[11px] font-normal text-gray-400">NISN: {{ $s->nisn ?? '-' }}</span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2.5 py-0.5 bg-gray-100 rounded-md text-xs font-bold text-gray-700">
                                            {{ $s->kelas ? $s->kelas->nama_kelas : '-' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[11px] font-bold rounded-full">
                                            ✓ Aktif
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="flex items-center justify-center space-x-1">
                                            <button type="button" onclick="bukaModalEdit('{{ $s->nis }}', '{{ addslashes($s->nama_siswa) }}', '{{ $s->id_kelas }}', '{{ $s->jenis_kelamin }}', '{{ $s->no_hp_wali }}')"
                                                class="p-1.5 text-gray-400 hover:text-[#405078] hover:bg-[#405078]/10 rounded-lg transition-colors cursor-pointer">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </button>
                                            <form action="{{ route('admin.siswa.destroy', $s->nis) }}" method="POST" onsubmit="return confirm('Hapus siswa {{ addslashes($s->nama_siswa) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-400 italic text-xs">
                                        Tidak ada data siswa yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($siswaList->hasPages())
                    <div class="px-6 py-4 bg-[#F8FAFC] border-t border-[#D1D9EB]">
                        {{ $siswaList->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT SISWA -->
<div id="modalEditSiswa" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl border border-[#D1D9EB] shadow-2xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 class="font-bold text-base text-[#1E2538]">Edit Data Siswa</h3>
            <button type="button" onclick="tutupModalEdit()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="formEditSiswa" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Lengkap Siswa *</label>
                <input type="text" name="nama_siswa" id="edit_nama_siswa" required
                    class="block w-full px-3.5 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078]">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Kelas *</label>
                <select name="id_kelas" id="edit_id_kelas" required
                    class="block w-full px-3.5 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078]">
                    @foreach ($kelasList as $k)
                        <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="edit_jenis_kelamin"
                        class="block w-full px-3 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538]">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">No HP Wali</label>
                    <input type="text" name="no_hp_wali" id="edit_no_hp_wali"
                        class="block w-full px-3 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538]">
                </div>
            </div>

            <div class="flex space-x-3 pt-2">
                <button type="submit" class="flex-1 py-2.5 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold transition-colors shadow-sm cursor-pointer">
                    Simpan Perubahan
                </button>
                <button type="button" onclick="tutupModalEdit()" class="px-4 py-2.5 border border-[#D1D9EB] hover:bg-gray-50 text-gray-600 rounded-xl text-xs font-semibold cursor-pointer">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let formOpen = true;
    function toggleFormPanel() {
        const formPanel = document.getElementById('formPanel');
        const tableCol = document.getElementById('tableCol');
        const foldText = document.getElementById('foldText');
        
        formOpen = !formOpen;
        if (!formOpen) {
            formPanel.classList.add('hidden');
            tableCol.classList.remove('lg:col-span-2');
            tableCol.classList.add('lg:col-span-3');
            foldText.innerText = 'Tampilkan Form Tambah';
        } else {
            formPanel.classList.remove('hidden');
            tableCol.classList.add('lg:col-span-2');
            tableCol.classList.remove('lg:col-span-3');
            foldText.innerText = 'Sembunyikan Form Tambah';
        }
    }

    // Live Instant Search (Poin 7)
    document.getElementById('liveSearchSiswa').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.siswa-row');
        rows.forEach(row => {
            const text = row.getAttribute('data-search') || '';
            if (text.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    function bukaModalEdit(nis, nama, idKelas, jk, noHp) {
        document.getElementById('edit_nama_siswa').value = nama;
        document.getElementById('edit_id_kelas').value = idKelas;
        document.getElementById('edit_jenis_kelamin').value = jk || 'L';
        document.getElementById('edit_no_hp_wali').value = noHp || '';
        document.getElementById('formEditSiswa').action = "{{ url('/admin/siswa') }}/" + nis;
        document.getElementById('modalEditSiswa').classList.remove('hidden');
    }

    function tutupModalEdit() {
        document.getElementById('modalEditSiswa').classList.add('hidden');
    }
</script>
@endsection