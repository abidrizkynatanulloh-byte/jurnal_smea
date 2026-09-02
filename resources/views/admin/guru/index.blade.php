@extends('layouts.app')

@section('title', 'Kelola Guru & Pegawai - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Data Guru & Pegawai</h1>
            <p class="text-xs text-gray-500 mt-1">
                Total terdaftar: <span class="font-semibold text-[#405078]">{{ $totalGuru }} Pegawai Aktif</span>
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="px-4 py-2 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-700 rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-xs cursor-pointer">
                <i data-lucide="panel-left" class="w-4 h-4 text-[#405078]"></i>
                <span id="foldText">Sembunyikan Form Tambah</span>
            </button>
            <a href="{{ route('admin.guru.trash') }}" class="px-4 py-2 bg-[#405078]/10 hover:bg-[#405078]/20 text-[#405078] rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
                <span>Tong Sampah</span>
            </a>
        </div>
    </div>

    <!-- Responsive Grid: Form on Left, List on Right -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- BAGIAN 1: FORM TAMBAH PEGAWAI BARU (DAPAT DI-FOLD) -->
        <div id="formPanel" class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-6 lg:sticky lg:top-6 transition-all duration-300">
            <h3 class="font-bold text-[#1E2538] text-base mb-4 flex items-center space-x-2">
                <i data-lucide="user-plus" class="w-5 h-5 text-[#405078]"></i>
                <span>Tambah Pegawai Baru</span>
            </h3>
            
            <form action="{{ route('admin.guru.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="nip" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">NIP / Username *</label>
                    <input type="text" name="nip" id="nip" value="{{ old('nip') }}" placeholder="Masukkan NIP / Kode Pegawai" required 
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <div>
                    <label for="nama_guru" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
                    <input type="text" name="nama_guru" id="nama_guru" value="{{ old('nama_guru') }}" placeholder="Contoh: Drs. Budi, M.Pd" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <div>
                    <label for="no_hp" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nomor HP (WhatsApp)</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx"
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <div>
                    <label for="kode_mapel" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Mata Pelajaran Utama</label>
                    <select name="kode_mapel" id="kode_mapel" 
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                        <option value="">-- Pilih Mapel (Opsional) --</option>
                        @foreach ($mapelList as $m)
                            <option value="{{ $m->kode_mapel }}" {{ old('kode_mapel') == $m->kode_mapel ? 'selected' : '' }}>
                                {{ $m->kode_mapel }} - {{ $m->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="role" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Role / Jabatan Sistem *</label>
                    <select name="role" id="role" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                        <option value="guru">Guru</option>
                        <option value="guru_piket">Guru Piket</option>
                        <option value="staf_tu">Staf TU</option>
                        <option value="satpam">Satpam</option>
                        <option value="kepala_sekolah">Kepala Sekolah</option>
                        <option value="wakasis_siswa">Wakil Kesiswaan (Siswa)</option>
                        <option value="wakasis_guru">Wakil Kurikulum / SDM (Guru)</option>
                    </select>
                </div>

                <div>
                    <label for="password" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Password Akun Login *</label>
                    <input type="password" name="password" id="password" placeholder="Masukkan password awal" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <button type="submit" class="w-full mt-2 py-3 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center space-x-2 cursor-pointer">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Pegawai</span>
                </button>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR & LIVE FILTER PEGAWAI -->
        <div id="tableCol" class="lg:col-span-2 space-y-6">
            
            <!-- STICKY FILTER CARD (DENGAN LIVE INSTANT SEARCH) -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-4 sticky top-6 z-20">
                <form action="{{ route('admin.guru.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Pencarian Cepat (Ketik Langsung Tersaring)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i data-lucide="search" class="w-4 h-4 text-[#8697C3]"></i>
                            </div>
                            <input type="text" name="search" id="liveSearchGuru" value="{{ request('search') }}" placeholder="Ketik Nama, NIP, atau Mata Pelajaran..." 
                                class="block w-full pl-9 pr-4 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                        </div>
                    </div>
                    
                    <div class="w-full sm:w-48">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Filter Mapel</label>
                        <select name="kode_mapel" onchange="this.form.submit()"
                            class="block w-full px-3 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                            <option value="">Semua Mapel</option>
                            @foreach ($mapelList as $m)
                                <option value="{{ $m->kode_mapel }}" {{ request('kode_mapel') == $m->kode_mapel ? 'selected' : '' }}>
                                    {{ $m->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if (request('search') || request('kode_mapel'))
                        <a href="{{ route('admin.guru.index') }}" class="px-3 py-2 border border-[#D1D9EB] hover:bg-gray-50 text-gray-600 rounded-xl text-xs font-semibold flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- TABEL DATA GURU (FIXED SCROLL DENGAN STICKY HEADER) -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto max-h-[580px] overflow-y-auto">
                    <table class="w-full text-left border-collapse" id="tableGuru">
                        <thead class="sticky top-0 bg-[#F8FAFC] z-10">
                            <tr class="text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                                <th class="py-3 px-4 text-center w-12">No</th>
                                <th class="py-3 px-4">Nama Lengkap & NIP</th>
                                <th class="py-3 px-4 w-40">Mata Pelajaran</th>
                                <th class="py-3 px-4 w-32">No. HP</th>
                                <th class="py-3 px-4 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse ($guruList as $index => $g)
                                <tr class="hover:bg-gray-50/50 transition-colors guru-row" data-search="{{ strtolower($g->nama_guru . ' ' . $g->nip . ' ' . ($g->mapel ? $g->mapel->nama_mapel : '')) }}">
                                    <td class="py-3.5 px-4 text-center font-medium text-gray-400 text-xs">{{ $guruList->firstItem() + $index }}</td>
                                    <td class="py-3.5 px-4">
                                        <p class="font-bold text-[#1E2538] text-xs">{{ $g->nama_guru }}</p>
                                        <p class="text-[11px] text-gray-400">NIP: {{ $g->nip }}</p>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2 py-0.5 bg-[#405078]/10 text-[#405078] rounded-md text-xs font-semibold">
                                            {{ $g->mapel ? $g->mapel->nama_mapel : '-' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-xs font-medium text-gray-600">
                                        {{ $g->no_hp ?? '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <form action="{{ route('admin.guru.destroy', $g->id_guru) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan {{ addslashes($g->nama_guru) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-400 italic text-xs">
                                        Tidak ada data guru yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($guruList->hasPages())
                    <div class="px-6 py-4 bg-[#F8FAFC] border-t border-[#D1D9EB]">
                        {{ $guruList->links() }}
                    </div>
                @endif
            </div>
        </div>
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
    document.getElementById('liveSearchGuru').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.guru-row');
        rows.forEach(row => {
            const text = row.getAttribute('data-search') || '';
            if (text.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection
