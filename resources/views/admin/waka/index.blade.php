@extends('layouts.app')

@section('title', 'Kelola Wakil Kepala Sekolah - Jurnal Esemkita')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Kelola Wakil Kepala Sekolah</h1>
            <p class="text-xs text-slate-500 mt-0.5">Atur wewenang Guru sebagai Waka Kesiswaan (Dispensasi Siswa) atau Waka Kurikulum/SDM (Izin Guru)</p>
        </div>
    </div>

    <!-- Alert / Notifikasi -->
    @if(session('success'))
        <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-xs font-semibold flex items-center space-x-2 shadow-2xs">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg text-xs font-semibold flex items-center space-x-2 shadow-2xs">
            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">
        
        <!-- FORM TAMBAH -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs p-5 lg:sticky lg:top-18 space-y-3.5">
            <div class="flex items-center space-x-2 pb-2.5 border-b border-slate-100">
                <div class="w-6 h-6 rounded bg-[#1E293B] text-white flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Angkat Menjadi Waka</h3>
            </div>
            
            <form action="{{ route('admin.waka.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Guru *</label>
                    <select id="guru-select" name="id_guru" required class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-[#1E293B] cursor-pointer">
                        <option value="">-- Pilih Nama / NIP Guru --</option>
                        @foreach($semuaGuru as $g)
                            <option value="{{ $g->id_guru }}">{{ $g->nama_guru }} ({{ $g->nip ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Jabatan Waka *</label>
                    <select name="tipe_waka" required class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-[#1E293B] cursor-pointer">
                        <option value="">-- Pilih Jabatan --</option>
                        <option value="wakasis_siswa">Waka Kesiswaan (Dispensasi Siswa)</option>
                        <option value="wakasis_guru">Waka Kurikulum / SDM (Izin Guru)</option>
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 px-4 bg-[#1E293B] hover:bg-[#0F172A] text-white rounded-lg text-xs font-bold transition-all shadow-xs flex items-center justify-center space-x-1.5 cursor-pointer">
                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                        <span>Terapkan Wewenang</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="lg:col-span-2 space-y-4">
            <!-- WAKA SISWA -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <h4 class="font-bold text-slate-900 text-xs flex items-center space-x-1.5 uppercase tracking-wider">
                        <i data-lucide="users" class="w-4 h-4 text-blue-600"></i>
                        <span>Waka Kesiswaan (Dispensasi Siswa)</span>
                    </h4>
                    <span class="text-xs text-slate-500 font-semibold">{{ count($wakaSiswa) }} Penugasan</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/50 text-slate-600 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200">
                                <th class="py-2.5 px-3.5">Nama Waka</th>
                                <th class="py-2.5 px-3.5 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($wakaSiswa as $ws)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2.5 px-3.5 font-medium text-slate-900">{{ $ws->guru->nama_guru ?? 'Data Guru Tidak Ditemukan' }}</td>
                                    <td class="py-2.5 px-3.5 text-center">
                                        <form action="{{ route('admin.waka.destroy', $ws->id) }}" method="POST" onsubmit="return confirm('Berhentikan wewenang Waka dan kembalikan menjadi Guru biasa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded text-xs font-semibold transition-colors cursor-pointer">Berhentikan</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="py-6 text-center text-slate-400 italic text-xs">Belum ada Waka Kesiswaan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- WAKA GURU -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <h4 class="font-bold text-slate-900 text-xs flex items-center space-x-1.5 uppercase tracking-wider">
                        <i data-lucide="award" class="w-4 h-4 text-indigo-600"></i>
                        <span>Waka Kurikulum / SDM (Izin Guru)</span>
                    </h4>
                    <span class="text-xs text-slate-500 font-semibold">{{ count($wakaGuru) }} Penugasan</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/50 text-slate-600 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200">
                                <th class="py-2.5 px-3.5">Nama Waka</th>
                                <th class="py-2.5 px-3.5 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($wakaGuru as $wg)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2.5 px-3.5 font-medium text-slate-900">{{ $wg->guru->nama_guru ?? 'Data Guru Tidak Ditemukan' }}</td>
                                    <td class="py-2.5 px-3.5 text-center">
                                        <form action="{{ route('admin.waka.destroy', $wg->id) }}" method="POST" onsubmit="return confirm('Berhentikan wewenang Waka dan kembalikan menjadi Guru biasa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded text-xs font-semibold transition-colors cursor-pointer">Berhentikan</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="py-6 text-center text-slate-400 italic text-xs">Belum ada Waka Kurikulum</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
