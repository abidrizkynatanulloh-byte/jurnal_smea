@extends('layouts.app')

@section('title', 'Manajemen Jadwal Mengajar - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Jadwal Mengajar</h1>
            <p class="text-xs text-gray-500 mt-1">
                Kelola dan petakan jadwal mengajar guru harian
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="px-4 py-2 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-700 rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-xs cursor-pointer">
                <i data-lucide="panel-left" class="w-4 h-4 text-[#405078]"></i>
                <span id="foldText">Sembunyikan Form Tambah</span>
            </button>
        </div>
    </div>

    <!-- Responsive Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- BAGIAN 1: FORM TAMBAH JADWAL (DAPAT DI-FOLD) -->
        <div id="formPanel" class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-6 lg:sticky lg:top-6 transition-all duration-300">
            <h3 class="font-bold text-[#1E2538] text-base mb-4 flex items-center space-x-2">
                <i data-lucide="calendar-plus" class="w-5 h-5 text-[#405078]"></i>
                <span>Tambah Jadwal Baru</span>
            </h3>
            
            <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="id_kelas" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Kelas *</label>
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

                <div>
                    <label for="hari" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Hari *</label>
                    <select name="hari" id="hari" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="jam_mulai" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Jam Mulai (Ke-) *</label>
                        <input type="number" name="jam_mulai" id="jam_mulai" min="1" max="15" value="{{ old('jam_mulai', 1) }}" required
                            class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078]">
                    </div>
                    <div>
                        <label for="jam_selesai" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Jam Selesai (Ke-) *</label>
                        <input type="number" name="jam_selesai" id="jam_selesai" min="1" max="15" value="{{ old('jam_selesai', 2) }}" required
                            class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078]">
                    </div>
                </div>

                <div>
                    <label for="id_guru" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Guru Pengajar *</label>
                    <select name="id_guru" id="id_guru" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] cursor-pointer">
                        <option value="">-- Pilih Guru --</option>
                        @foreach ($guruList as $g)
                            <option value="{{ $g->id_guru }}" {{ old('id_guru') == $g->id_guru ? 'selected' : '' }}>
                                {{ $g->nama_guru }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="kode_mapel" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Mata Pelajaran *</label>
                    <select name="kode_mapel" id="kode_mapel" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] cursor-pointer">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach ($mapelList as $m)
                            <option value="{{ $m->kode_mapel }}" {{ old('kode_mapel') == $m->kode_mapel ? 'selected' : '' }}>
                                {{ $m->kode_mapel }} - {{ $m->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="id_ruangan" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Ruangan Kelas *</label>
                    <select name="id_ruangan" id="id_ruangan" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] cursor-pointer">
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach ($ruanganList as $r)
                            <option value="{{ $r->id_ruangan }}" {{ old('id_ruangan') == $r->id_ruangan ? 'selected' : '' }}>
                                {{ $r->nama_ruangan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full mt-2 py-3 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center space-x-2 cursor-pointer">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Jadwal</span>
                </button>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR & LIVE FILTER JADWAL -->
        <div id="tableCol" class="lg:col-span-2 space-y-6">
            
            <!-- STICKY FILTER CARD (DENGAN LIVE INSTANT SEARCH) -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-4 sticky top-6 z-20">
                <form action="{{ route('admin.jadwal.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Pencarian Cepat (Ketik Langsung Tersaring)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i data-lucide="search" class="w-4 h-4 text-[#8697C3]"></i>
                            </div>
                            <input type="text" name="search" id="liveSearchJadwal" value="{{ request('search') }}" placeholder="Ketik Nama Guru, Kelas, Mapel, Hari..." 
                                class="block w-full pl-9 pr-4 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                        </div>
                    </div>
                    
                    <div class="w-full sm:w-36">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Filter Hari</label>
                        <select name="hari" onchange="this.form.submit()"
                            class="block w-full px-3 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078] cursor-pointer">
                            <option value="">Semua Hari</option>
                            <option value="Senin" {{ request('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                            <option value="Selasa" {{ request('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                            <option value="Rabu" {{ request('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                            <option value="Kamis" {{ request('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                            <option value="Jumat" {{ request('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        </select>
                    </div>

                    @if (request('search') || request('hari') || request('id_kelas'))
                        <a href="{{ route('admin.jadwal.index') }}" class="px-3 py-2 border border-[#D1D9EB] hover:bg-gray-50 text-gray-600 rounded-xl text-xs font-semibold flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- TABLE CARD (FIXED SCROLL DENGAN STICKY HEADER) -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto max-h-[580px] overflow-y-auto">
                    <table class="w-full text-left border-collapse" id="tableJadwal">
                        <thead class="sticky top-0 bg-[#F8FAFC] z-10">
                            <tr class="text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                                <th class="py-3 px-4">Hari / Sesi</th>
                                <th class="py-3 px-4">Kelas & Ruang</th>
                                <th class="py-3 px-4">Mata Pelajaran</th>
                                <th class="py-3 px-4">Guru Pengajar</th>
                                <th class="py-3 px-4 text-center w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse ($jadwalList as $j)
                                <tr class="hover:bg-gray-50/50 transition-colors jadwal-row" data-search="{{ strtolower($j->hari . ' ' . ($j->guru ? $j->guru->nama_guru : '') . ' ' . ($j->kelas ? $j->kelas->nama_kelas : '') . ' ' . ($j->mapel ? $j->mapel->nama_mapel : '') . ' ' . ($j->ruangan ? $j->ruangan->nama_ruangan : '')) }}">
                                    <td class="py-3.5 px-4">
                                        <p class="font-bold text-[#1E2538] text-xs">{{ $j->hari }}</p>
                                        <p class="text-[11px] text-gray-400">Jam Ke-{{ $j->jam_mulai }} s/d {{ $j->jam_selesai }}</p>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs font-bold">{{ $j->kelas ? $j->kelas->nama_kelas : '-' }}</span>
                                        <span class="text-[11px] text-gray-400 block mt-0.5">{{ $j->ruangan ? $j->ruangan->nama_ruangan : '-' }}</span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <p class="font-bold text-[#405078] text-xs">{{ $j->mapel ? $j->mapel->nama_mapel : '-' }}</p>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <p class="font-medium text-[#1E2538] text-xs">{{ $j->guru ? $j->guru->nama_guru : '-' }}</p>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <form action="{{ route('admin.jadwal.destroy', $j->id_jadwal) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus Jadwal">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-400 italic text-xs">
                                        Tidak ada data jadwal yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($jadwalList->hasPages())
                    <div class="px-6 py-4 bg-[#F8FAFC] border-t border-[#D1D9EB]">
                        {{ $jadwalList->links() }}
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
    document.getElementById('liveSearchJadwal').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.jadwal-row');
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