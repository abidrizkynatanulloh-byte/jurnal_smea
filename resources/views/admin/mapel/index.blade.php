@extends('layouts.app')

@section('title', 'Kelola Mata Pelajaran - Jurnal Esemkita')

@section('content')
<div class="space-y-5">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
        <div>
            <h1 class="text-xl font-extrabold text-[#1E2538] tracking-tight">Mata Pelajaran</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola seluruh kurikulum dan kode mata pelajaran sekolah</p>
        </div>
        <div class="flex items-center space-x-2.5">
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="px-3 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-[#1E2538] rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5 text-[#405078]"></i>
                <span id="foldText">Sembunyikan Form Tambah</span>
            </button>
        </div>
    </div>

    <!-- Alert / Notifikasi -->
    @if (session('success'))
        <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center space-x-2 shadow-2xs">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-semibold shadow-2xs">
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Responsive Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">
        <!-- BAGIAN 1: FORM TAMBAH MAPEL BARU (STICKY & FOLDABLE) -->
        <div id="formPanel" class="bg-white border border-slate-200 rounded-2xl p-4.5 shadow-xs space-y-3 lg:sticky lg:top-20 z-10 transition-all duration-300">
            <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <i data-lucide="book-plus" class="w-4 h-4 text-[#405078]"></i>
                    <h3 class="font-bold text-[#1E2538] text-xs uppercase tracking-wider">Tambah Mapel Baru</h3>
                </div>
            </div>
            
            <form action="{{ route('admin.mapel.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label for="kode_mapel" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kode Mapel *</label>
                    <input type="text" name="kode_mapel" id="kode_mapel" value="{{ old('kode_mapel') }}" placeholder="Contoh: MP-MAT" required 
                        class="block w-full px-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] placeholder-slate-400 focus:outline-none focus:border-[#405078] focus:ring-1 focus:ring-[#405078]/20 transition-all">
                </div>

                <div>
                    <label for="nama_mapel" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Mata Pelajaran *</label>
                    <input type="text" name="nama_mapel" id="nama_mapel" value="{{ old('nama_mapel') }}" placeholder="Masukkan Nama Mapel" required
                        class="block w-full px-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] placeholder-slate-400 focus:outline-none focus:border-[#405078] focus:ring-1 focus:ring-[#405078]/20 transition-all">
                </div>

                <div class="pt-1.5">
                    <button type="submit" class="w-full py-2 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center justify-center space-x-1.5 cursor-pointer">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        <span>Simpan Mapel</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR & LIVE FILTER MAPEL -->
        <div id="tableCol" class="lg:col-span-2 space-y-3.5">
            <!-- STICKY FILTER CARD (DENGAN LIVE INSTANT SEARCH) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-3.5 shadow-xs sticky top-20 z-20">
                <form action="{{ route('admin.mapel.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pencarian Cepat (Ketik Langsung Tersaring)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="search" class="w-3.5 h-3.5 text-[#8697C3]"></i>
                            </div>
                            <input type="text" name="search" id="liveSearchMapel" value="{{ request('search') }}" placeholder="Ketik Kode atau Nama Mapel..." 
                                class="block w-full pl-8.5 pr-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] placeholder-slate-400 focus:outline-none focus:border-[#405078] focus:ring-1 focus:ring-[#405078]/20 transition-all">
                        </div>
                    </div>

                    @if (request('search'))
                        <a href="{{ route('admin.mapel.index') }}" class="px-3 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-xl text-xs font-semibold flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- TABLE CARD (FIXED SCROLL DENGAN STICKY HEADER) -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden flex flex-col">
                <div class="overflow-x-auto max-h-[calc(100vh-270px)] min-h-[380px] overflow-y-auto">
                    <table class="w-full text-left border-collapse text-xs" id="tableMapel">
                        <thead class="sticky top-0 bg-[#F8FAFC] z-10">
                            <tr class="text-slate-500 font-semibold uppercase tracking-wider border-b border-slate-200">
                                <th class="py-2.5 px-3 w-32">Kode Mapel</th>
                                <th class="py-2.5 px-3">Nama Mata Pelajaran</th>
                                <th class="py-2.5 px-3 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-600">
                            @forelse ($mapelList as $m)
                                <tr class="hover:bg-slate-50/60 transition-colors mapel-row" data-search="{{ strtolower($m->kode_mapel . ' ' . $m->nama_mapel) }}">
                                    <td class="py-2 px-3 font-bold text-[#405078] text-xs">{{ $m->kode_mapel }}</td>
                                    <td class="py-2 px-3 font-medium text-[#1E2538] text-xs">{{ $m->nama_mapel }}</td>
                                    <td class="py-2 px-3 text-center">
                                        <div class="flex items-center justify-center space-x-1">
                                            <button type="button" 
                                                onclick="openEditMapelModal('{{ $m->kode_mapel }}', '{{ addslashes($m->nama_mapel) }}')"
                                                class="p-1.5 text-[#405078] hover:bg-[#405078]/10 rounded-lg transition-colors cursor-pointer" title="Edit Mapel">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                            </button>
                                            <form action="{{ route('admin.mapel.destroy', $m->kode_mapel) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mapel {{ addslashes($m->nama_mapel) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus Mapel">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-slate-400 italic text-xs">
                                        Tidak ada data mata pelajaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION BAR (SESUAI GAMBAR 2) -->
                <x-pagination-bar :paginator="$mapelList" />
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT MAPEL -->
<div id="modalEditMapel" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-2xs flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-md w-full p-5 space-y-3.5">
        <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
            <h3 class="font-bold text-[#1E2538] text-sm flex items-center space-x-1.5">
                <i data-lucide="edit-3" class="w-4 h-4 text-[#405078]"></i>
                <span>Edit Mata Pelajaran</span>
            </h3>
            <button type="button" onclick="closeEditMapelModal()" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="formEditMapel" method="POST" class="space-y-3">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kode Mapel</label>
                <input type="text" id="edit_kode_mapel" disabled
                    class="block w-full px-3 py-1.5 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-500 font-bold">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Mata Pelajaran *</label>
                <input type="text" name="nama_mapel" id="edit_nama_mapel" required
                    class="block w-full px-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078]">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2.5 border-t border-slate-100">
                <button type="button" onclick="closeEditMapelModal()" class="px-3.5 py-1.5 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-4 py-1.5 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold shadow-xs cursor-pointer">
                    Simpan Perubahan
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
            foldText.innerText = 'Buka Form Tambah';
        } else {
            formPanel.classList.remove('hidden');
            tableCol.classList.add('lg:col-span-2');
            tableCol.classList.remove('lg:col-span-3');
            foldText.innerText = 'Sembunyikan Form Tambah';
        }
    }

    function openEditMapelModal(kode, nama) {
        document.getElementById('edit_kode_mapel').value = kode;
        document.getElementById('edit_nama_mapel').value = nama;
        document.getElementById('formEditMapel').action = `/admin/mapel/${kode}`;
        document.getElementById('modalEditMapel').classList.remove('hidden');
    }

    function closeEditMapelModal() {
        document.getElementById('modalEditMapel').classList.add('hidden');
    }

    // Live Instant Search
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('liveSearchMapel');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('.mapel-row');
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
