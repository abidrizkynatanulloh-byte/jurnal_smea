@extends('layouts.app')

@section('title', 'Kelola Mata Pelajaran - Jurnal Esemkita')

@section('content')
<div class="space-y-4">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Mata Pelajaran</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola seluruh kurikulum dan kode mata pelajaran sekolah</p>
        </div>
        <div>
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="px-3.5 py-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5 text-slate-600"></i>
                <span id="foldText">Sembunyikan Form Tambah</span>
            </button>
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

    <!-- Responsive Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">
        <!-- BAGIAN 1: FORM TAMBAH MAPEL BARU (STICKY & FOLDABLE) -->
        <div id="formPanel" class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-3.5 lg:sticky lg:top-18 z-10">
            <div class="flex items-center space-x-2 pb-2.5 border-b border-slate-100">
                <div class="w-6 h-6 rounded bg-[#1E293B] text-white flex items-center justify-center">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Tambah Mapel Baru</h3>
            </div>
            
            <form action="{{ route('admin.mapel.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label for="kode_mapel" class="block text-xs font-semibold text-slate-700 mb-1">Kode Mapel *</label>
                    <input type="text" name="kode_mapel" id="kode_mapel" value="{{ old('kode_mapel') }}" placeholder="Contoh: MP-MAT" required 
                        class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B] focus:ring-1 focus:ring-[#1E293B] transition-all">
                </div>

                <div>
                    <label for="nama_mapel" class="block text-xs font-semibold text-slate-700 mb-1">Nama Mata Pelajaran *</label>
                    <input type="text" name="nama_mapel" id="nama_mapel" value="{{ old('nama_mapel') }}" placeholder="Masukkan Nama Mapel" required
                        class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B] focus:ring-1 focus:ring-[#1E293B] transition-all">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2 px-4 bg-[#1E293B] hover:bg-[#0F172A] text-white rounded-lg text-xs font-bold transition-all shadow-xs flex items-center justify-center space-x-1.5 cursor-pointer">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        <span>Simpan Mapel</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR & LIVE FILTER MAPEL (ENCLOSED INSIDE PROPER DATA CARD) -->
        <div id="tableCol" class="lg:col-span-2 space-y-3.5">
            <!-- DATA TABLE CARD (DENGAN HEADER KONTROL SESUAI GAMBAR 5) -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden flex flex-col">
                <!-- Top Control Bar (Search & Counter) -->
                <div class="p-3.5 border-b border-slate-200 bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <form action="{{ route('admin.mapel.index') }}" method="GET" class="relative flex-1 max-w-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        </div>
                        <input type="text" name="search" id="liveSearchMapel" value="{{ request('search') }}" placeholder="Cari Kode atau Nama Mapel..." 
                            class="block w-full pl-9 pr-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B] focus:ring-1 focus:ring-[#1E293B] transition-all">
                    </form>

                    <div class="flex items-center space-x-2 text-xs text-slate-500 font-semibold">
                        <span>Total: <strong class="text-slate-900">{{ $mapelList->total() }}</strong> Mata Pelajaran</span>
                        @if (request('search'))
                            <a href="{{ route('admin.mapel.index') }}" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded text-xs transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Scrollable Table Body -->
                <div class="overflow-x-auto max-h-[calc(100vh-270px)] min-h-[380px] overflow-y-auto">
                    <table class="w-full text-left border-collapse text-xs" id="tableMapel">
                        <thead class="sticky top-0 bg-slate-50 border-b border-slate-200 z-10">
                            <tr class="text-slate-600 font-bold uppercase tracking-wider text-[11px]">
                                <th class="py-3 px-4 w-36">Kode Mapel</th>
                                <th class="py-3 px-4">Nama Mata Pelajaran</th>
                                <th class="py-3 px-4 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($mapelList as $m)
                                <tr class="hover:bg-slate-50/80 transition-colors mapel-row" data-search="{{ strtolower($m->kode_mapel . ' ' . $m->nama_mapel) }}">
                                    <td class="py-3 px-4 font-bold text-slate-900 font-mono text-xs">{{ $m->kode_mapel }}</td>
                                    <td class="py-3 px-4 font-medium text-slate-800 text-xs">{{ $m->nama_mapel }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center space-x-1.5">
                                            <button type="button" 
                                                onclick="openEditMapelModal('{{ $m->kode_mapel }}', '{{ addslashes($m->nama_mapel) }}')"
                                                class="w-7 h-7 rounded border border-slate-200 hover:border-slate-300 hover:bg-slate-100 text-slate-600 hover:text-slate-900 flex items-center justify-center transition-colors cursor-pointer" title="Edit Mapel">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                            </button>
                                            <form action="{{ route('admin.mapel.destroy', $m->kode_mapel) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mapel {{ addslashes($m->nama_mapel) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-7 h-7 rounded border border-slate-200 hover:border-rose-300 hover:bg-rose-50 text-slate-600 hover:text-rose-600 flex items-center justify-center transition-colors cursor-pointer" title="Hapus Mapel">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-12 text-center text-slate-400 italic text-xs">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                        Tidak ada data mata pelajaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION BAR (SESUAI GAMBAR 5) -->
                <x-pagination-bar :paginator="$mapelList" />
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT MAPEL -->
<div id="modalEditMapel" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-xl border border-slate-200 shadow-xl max-w-sm w-full p-5 space-y-4">
        <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
            <h3 class="font-bold text-slate-900 text-sm flex items-center space-x-1.5">
                <i data-lucide="edit-3" class="w-4 h-4 text-slate-700"></i>
                <span>Edit Mata Pelajaran</span>
            </h3>
            <button type="button" onclick="closeEditMapelModal()" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="formEditMapel" method="POST" class="space-y-3.5">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_kode_mapel" class="block text-xs font-semibold text-slate-700 mb-1">Kode Mapel *</label>
                <input type="text" name="kode_mapel" id="edit_kode_mapel" required
                    class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-[#1E293B] focus:ring-1 focus:ring-[#1E293B]">
            </div>

            <div>
                <label for="edit_nama_mapel" class="block text-xs font-semibold text-slate-700 mb-1">Nama Mata Pelajaran *</label>
                <input type="text" name="nama_mapel" id="edit_nama_mapel" required
                    class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-[#1E293B] focus:ring-1 focus:ring-[#1E293B]">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeEditMapelModal()" class="px-3.5 py-1.5 border border-slate-300 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 cursor-pointer">
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
    let isFolded = false;
    function toggleFormPanel() {
        const formPanel = document.getElementById('formPanel');
        const tableCol = document.getElementById('tableCol');
        const foldText = document.getElementById('foldText');

        if (!isFolded) {
            formPanel.style.display = 'none';
            tableCol.className = 'lg:col-span-3 space-y-3.5';
            foldText.innerText = 'Buka Form Tambah';
            isFolded = true;
        } else {
            formPanel.style.display = 'block';
            tableCol.className = 'lg:col-span-2 space-y-3.5';
            foldText.innerText = 'Sembunyikan Form Tambah';
            isFolded = false;
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
