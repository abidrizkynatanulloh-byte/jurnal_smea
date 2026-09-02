@extends('layouts.app')

@section('title', 'Kelola Mata Pelajaran - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Mata Pelajaran</h1>
            <p class="text-xs text-gray-500 mt-1">
                Total terdaftar: <span class="font-semibold text-[#405078]">{{ $totalMapel }} Mata Pelajaran</span>
            </p>
        </div>
    </div>

    <!-- Responsive Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- BAGIAN 1: FORM TAMBAH MAPEL BARU -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-6 lg:sticky lg:top-8">
            <h3 class="font-bold text-[#1E2538] text-base mb-4 flex items-center space-x-2">
                <i data-lucide="book-plus" class="w-5 h-5 text-[#405078]"></i>
                <span>Tambah Mapel Baru</span>
            </h3>
            
            <form action="{{ route('admin.mapel.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="kode_mapel" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Kode Mapel</label>
                    <input type="text" name="kode_mapel" id="kode_mapel" value="{{ old('kode_mapel') }}" placeholder="Contoh: MP-MAT" required 
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <div>
                    <label for="nama_mapel" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nama Mata Pelajaran</label>
                    <input type="text" name="nama_mapel" id="nama_mapel" value="{{ old('nama_mapel') }}" placeholder="Masukkan Nama Mapel" required
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <button type="submit" class="w-full mt-2 py-3 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center space-x-2 cursor-pointer">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Mapel</span>
                </button>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR & FILTER MAPEL -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- FILTER CARD -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-5">
                <form action="{{ route('admin.mapel.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Cari Mapel</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / Kode Mapel..." 
                                class="block w-full pl-9 pr-4 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                        </div>
                    </div>

                    <div class="flex space-x-2 w-full sm:w-auto">
                        <button type="submit" class="flex-1 sm:flex-initial px-5 py-2 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-sm font-semibold transition-colors flex items-center justify-center space-x-1.5 cursor-pointer shadow-sm">
                            <i data-lucide="filter" class="w-4 h-4"></i>
                            <span>Cari</span>
                        </button>
                        @if (request('search'))
                            <a href="{{ route('admin.mapel.index') }}" class="px-4 py-2 border border-[#D1D9EB] hover:bg-gray-50 text-gray-500 hover:text-[#1E2538] rounded-xl text-sm font-semibold transition-colors flex items-center justify-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- TABLE CARD -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#F8FAFC] text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                                <th class="py-4 px-6 w-1/3">Kode Mapel</th>
                                <th class="py-4 px-6">Nama Mata Pelajaran</th>
                                <th class="py-4 px-6 text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse ($mapelList as $m)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-6 font-bold text-[#405078]">{{ $m->kode_mapel }}</td>
                                    <td class="py-4 px-6 font-medium text-[#1E2538]">{{ $m->nama_mapel }}</td>
                                    <td class="py-4 px-6 text-center" x-data="{ editModal: false }">
                                        <button @click="editModal = true" type="button" class="p-1.5 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors cursor-pointer mr-1" title="Edit Mapel">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </button>

                                        <form action="{{ route('admin.mapel.destroy', $m->kode_mapel) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus Mapel">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>

                                        <!-- Edit Modal -->
                                        <div x-show="editModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                <div x-show="editModal" @click="editModal = false" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                <div x-show="editModal" x-transition class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                    <form action="{{ route('admin.mapel.update', $m->kode_mapel) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                            <div class="sm:flex sm:items-start">
                                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                                    <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                                                        Edit Mata Pelajaran
                                                                    </h3>
                                                                    <div class="mt-4 space-y-4 text-left">
                                                                        <div>
                                                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kode Mapel</label>
                                                                            <input type="text" name="kode_mapel" value="{{ $m->kode_mapel }}" required class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                                                        </div>
                                                                        <div>
                                                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Mata Pelajaran</label>
                                                                            <input type="text" name="nama_mapel" value="{{ $m->nama_mapel }}" required class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-[#405078] text-base font-medium text-white hover:bg-[#2F3C5C] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#405078] sm:ml-3 sm:w-auto sm:text-sm">
                                                                Simpan Perubahan
                                                            </button>
                                                            <button type="button" @click="editModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#405078] sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                                Batal
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-gray-400 italic">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                        Tidak ada mata pelajaran yang cocok dengan pencarian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION FOOTER -->
                @if ($mapelList->hasPages())
                    <div class="px-6 py-4 bg-[#F8FAFC] border-t border-[#D1D9EB] flex items-center justify-between">
                        <div class="text-xs text-gray-500">
                            Menampilkan halaman <span class="font-semibold text-[#1E2538]">{{ $mapelList->currentPage() }}</span> dari <span class="font-semibold text-[#1E2538]">{{ $mapelList->lastPage() }}</span>
                        </div>
                        <div class="inline-flex space-x-1.5">
                            @if ($mapelList->previousPageUrl())
                                <a href="{{ $mapelList->previousPageUrl() }}" class="px-3.5 py-1.5 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-600 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1">
                                    <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
                                    <span>Sebelumnya</span>
                                </a>
                            @endif
                            @if ($mapelList->nextPageUrl())
                                <a href="{{ $mapelList->nextPageUrl() }}" class="px-3.5 py-1.5 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-600 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1">
                                    <span>Selanjutnya</span>
                                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection
