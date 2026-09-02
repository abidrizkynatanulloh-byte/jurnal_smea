@extends('layouts.app')

@section('title', 'Kelola Wakil Kepala Sekolah - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Kelola Wakil Kepala Sekolah</h1>
            <p class="text-xs text-gray-500 mt-1">Atur wewenang Guru sebagai Waka Kesiswaan (Siswa) atau Waka Kurikulum (Guru)</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- FORM TAMBAH -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-6 lg:sticky lg:top-8">
            <h3 class="font-bold text-[#1E2538] text-base mb-4 flex items-center space-x-2">
                <i data-lucide="user-plus" class="w-5 h-5 text-[#405078]"></i>
                <span>Angkat Menjadi Waka</span>
            </h3>
            
            <form action="{{ route('admin.waka.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Pilih Guru</label>
                    <select id="guru-select" name="id_guru" required class="block w-full text-sm" placeholder="Cari Nama / NIP Guru...">
                        <option value="">-- Cari Nama / NIP Guru --</option>
                        @foreach($semuaGuru as $g)
                            <option value="{{ $g->id_guru }}" data-nip="{{ $g->nip }}">{{ $g->nama_guru }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Pilih Jabatan Waka</label>
                    <select name="tipe_waka" required class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                        <option value="">-- Pilih Jabatan --</option>
                        <option value="wakasis_siswa">Waka Kesiswaan (Dispensasi Siswa)</option>
                        <option value="wakasis_guru">Waka Kurikulum / SDM (Izin Guru)</option>
                    </select>
                </div>

                <button type="submit" class="w-full mt-2 py-3 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center space-x-2 cursor-pointer">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Terapkan Wewenang</span>
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <!-- WAKA SISWA -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-[#F8FAFC] border-b border-[#D1D9EB]">
                    <h4 class="font-bold text-[#1E2538] text-sm flex items-center space-x-2">
                        <i data-lucide="users" class="w-4 h-4 text-blue-500"></i>
                        <span>Waka Kesiswaan (Siswa)</span>
                    </h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                                <th class="py-4 px-6">Nama Waka</th>
                                <th class="py-4 px-6 text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse($wakaSiswa as $ws)
                                <tr>
                                    <td class="py-4 px-6 font-medium text-[#1E2538]">{{ $ws->guru->nama_guru ?? 'Data Guru Tidak Ditemukan' }}</td>
                                    <td class="py-4 px-6 text-center">
                                        <form action="{{ route('admin.waka.destroy', $ws->id) }}" method="POST" onsubmit="return confirm('Berhentikan wewenang Waka dan kembalikan menjadi Guru biasa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3.5 py-1.5 border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 rounded-lg text-xs font-bold transition-colors">Berhentikan</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="py-6 text-center text-gray-400 italic">Belum ada Waka Kesiswaan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- WAKA GURU -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-[#F8FAFC] border-b border-[#D1D9EB]">
                    <h4 class="font-bold text-[#1E2538] text-sm flex items-center space-x-2">
                        <i data-lucide="book-open" class="w-4 h-4 text-amber-500"></i>
                        <span>Waka Kurikulum / SDM (Guru)</span>
                    </h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                                <th class="py-4 px-6">Nama Waka</th>
                                <th class="py-4 px-6 text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse($wakaGuru as $wg)
                                <tr>
                                    <td class="py-4 px-6 font-medium text-[#1E2538]">{{ $wg->guru->nama_guru ?? 'Data Guru Tidak Ditemukan' }}</td>
                                    <td class="py-4 px-6 text-center">
                                        <form action="{{ route('admin.waka.destroy', $wg->id) }}" method="POST" onsubmit="return confirm('Berhentikan wewenang Waka dan kembalikan menjadi Guru biasa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3.5 py-1.5 border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 rounded-lg text-xs font-bold transition-colors">Berhentikan</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="py-6 text-center text-gray-400 italic">Belum ada Waka Kurikulum</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        new TomSelect("#guru-select", {
            searchField: ['text', 'nip'],
            render: {
                option: function(data, escape) {
                    return '<div><span class="font-medium">' + escape(data.text) + '</span>' +
                           '<span class="text-xs text-gray-400 ml-2">NIP: ' + escape(data.nip || '-') + '</span></div>';
                },
                item: function(data, escape) {
                    return '<div>' + escape(data.text) + '</div>';
                }
            }
        });
    });
</script>
@endpush
@endsection
