@extends('layouts.app')

@section('title', 'Kelola Wakil Kepala Sekolah - Jurnal Esemkita')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white dark:bg-[#242A35] p-4.5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xs">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-slate-100 tracking-tight flex items-center space-x-2">
                <i data-lucide="shield" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                <span>Kelola Wakil Kepala Sekolah</span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Atur wewenang Guru sebagai Waka Kesiswaan, Waka Kurikulum (Tahap 1), atau Waka SDM / Kepegawaian (Tahap 2).
            </p>
        </div>
    </div>



    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">
        
        <!-- FORM TAMBAH / ANGKAT WAKA -->
        <div class="bg-white dark:bg-[#242A35] border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs p-4 lg:sticky lg:top-8 space-y-3">
            <div class="flex items-center space-x-2 pb-2.5 border-b border-slate-100 dark:border-slate-800">
                <div class="w-6 h-6 rounded-lg bg-[#1E2538] dark:bg-blue-600 text-white flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                </div>
                <h3 class="font-bold text-slate-900 dark:text-slate-100 text-xs uppercase tracking-wider">Angkat Menjadi Waka</h3>
            </div>
            
            <form action="{{ route('admin.waka.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Pilih Guru *</label>
                    <select id="guru-select" name="id_guru" required class="searchable-select block w-full h-8.5 px-2.5 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-blue-600 cursor-pointer">
                        <option value="">-- Pilih Nama / NIP Guru --</option>
                        @foreach($semuaGuru as $g)
                            <option value="{{ $g->id_guru }}">{{ $g->nama_guru }} ({{ $g->nip ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Pilih Jabatan Waka *</label>
                    <select name="tipe_waka" required class="block w-full h-8.5 px-2.5 bg-white dark:bg-[#1A212D] border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:border-blue-600 cursor-pointer">
                        <option value="">-- Pilih Jabatan Waka --</option>
                        <option value="wakasis_siswa">🏆 Waka Kesiswaan (Dispensasi Siswa)</option>
                        <option value="waka_kurikulum">📘 Waka Kurikulum (ACC Izin Guru - Tahap 1)</option>
                        <option value="waka_sdm">👔 Waka SDM / Kepegawaian (ACC Izin Guru - Tahap 2)</option>
                    </select>
                </div>

                <div class="pt-1.5">
                    <button type="submit" class="w-full min-h-[44px] py-3 px-5 bg-[#1E2538] hover:bg-[#121724] dark:bg-blue-600 dark:hover:bg-blue-700 text-white rounded-xl text-xs font-bold tracking-wide transition-all flex items-center justify-center space-x-2 shadow-xs cursor-pointer shrink-0">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>Terapkan Wewenang Waka</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- DAFTAR KARTU 3 JABATAN WAKA -->
        <div class="lg:col-span-2 space-y-4">
            
            <!-- 1. WAKA KESISWAAN (DISPENSASI SISWA) -->
            <div class="bg-white dark:bg-[#242A35] border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
                <div class="px-4 py-3 bg-slate-50 dark:bg-[#1A2836] border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <h4 class="font-bold text-slate-900 dark:text-blue-300 text-xs flex items-center space-x-2 uppercase tracking-wider">
                        <i data-lucide="users" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i>
                        <span>1. Waka Kesiswaan (Dispensasi Siswa)</span>
                    </h4>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold">{{ count($wakaSiswa) }} Penugasan</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-white dark:bg-[#242A35] text-slate-500 dark:text-slate-400 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                                <th class="py-2.5 px-4">Nama Waka Kesiswaan</th>
                                <th class="py-2.5 px-4 text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                            @forelse($wakaSiswa as $ws)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="py-2.5 px-4 font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $ws->guru ? $ws->guru->nama_guru : 'Data Guru Tidak Ditemukan' }}
                                        <div class="text-[10px] font-normal text-slate-400">NIP: {{ $ws->guru ? $ws->guru->nip ?? '-' : '-' }}</div>
                                    </td>
                                    <td class="py-2.5 px-4 text-center">
                                        <form action="{{ route('admin.waka.destroy', $ws->id) }}" method="POST" onsubmit="return confirm('Berhentikan wewenang Waka Kesiswaan dan kembalikan menjadi Guru biasa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="h-7 px-2.5 border border-rose-200 dark:border-rose-900 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/60 text-rose-700 dark:text-rose-300 rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                                                Berhentikan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="py-5 text-center text-slate-400 dark:text-slate-500 italic text-xs">Belum ada Waka Kesiswaan yang ditugaskan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. WAKA KURIKULUM (ACC IZIN GURU TAHAP 1) -->
            <div class="bg-white dark:bg-[#242A35] border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
                <div class="px-4 py-3 bg-slate-50 dark:bg-[#1A2836] border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <h4 class="font-bold text-slate-900 dark:text-indigo-300 text-xs flex items-center space-x-2 uppercase tracking-wider">
                        <i data-lucide="book-marked" class="w-4 h-4 text-indigo-600 dark:text-indigo-400"></i>
                        <span>2. Waka Kurikulum (ACC Izin Guru - Tahap 1)</span>
                    </h4>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold">{{ count($wakaKurikulum) }} Penugasan</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-white dark:bg-[#242A35] text-slate-500 dark:text-slate-400 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                                <th class="py-2.5 px-4">Nama Waka Kurikulum</th>
                                <th class="py-2.5 px-4 text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                            @forelse($wakaKurikulum as $wk)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="py-2.5 px-4 font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $wk->guru ? $wk->guru->nama_guru : 'Data Guru Tidak Ditemukan' }}
                                        <div class="text-[10px] font-normal text-slate-400">NIP: {{ $wk->guru ? $wk->guru->nip ?? '-' : '-' }}</div>
                                    </td>
                                    <td class="py-2.5 px-4 text-center">
                                        <form action="{{ route('admin.waka.destroy', $wk->id) }}" method="POST" onsubmit="return confirm('Berhentikan wewenang Waka Kurikulum dan kembalikan menjadi Guru biasa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="h-7 px-2.5 border border-rose-200 dark:border-rose-900 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/60 text-rose-700 dark:text-rose-300 rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                                                Berhentikan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="py-5 text-center text-slate-400 dark:text-slate-500 italic text-xs">Belum ada Waka Kurikulum yang ditugaskan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. WAKA SDM / KEPEGAWAIAN (ACC IZIN GURU TAHAP 2) -->
            <div class="bg-white dark:bg-[#242A35] border border-slate-200 dark:border-slate-800 rounded-xl shadow-xs overflow-hidden">
                <div class="px-4 py-3 bg-slate-50 dark:bg-[#1A2836] border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <h4 class="font-bold text-slate-900 dark:text-purple-300 text-xs flex items-center space-x-2 uppercase tracking-wider">
                        <i data-lucide="user-check" class="w-4 h-4 text-purple-600 dark:text-purple-400"></i>
                        <span>3. Waka SDM / Kepegawaian (ACC Izin Guru - Tahap 2)</span>
                    </h4>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold">{{ count($wakaSdm) }} Penugasan</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-white dark:bg-[#242A35] text-slate-500 dark:text-slate-400 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                                <th class="py-2.5 px-4">Nama Waka SDM / Kepegawaian</th>
                                <th class="py-2.5 px-4 text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                            @forelse($wakaSdm as $wsdm)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="py-2.5 px-4 font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $wsdm->guru ? $wsdm->guru->nama_guru : 'Data Guru Tidak Ditemukan' }}
                                        <div class="text-[10px] font-normal text-slate-400">NIP: {{ $wsdm->guru ? $wsdm->guru->nip ?? '-' : '-' }}</div>
                                    </td>
                                    <td class="py-2.5 px-4 text-center">
                                        <form action="{{ route('admin.waka.destroy', $wsdm->id) }}" method="POST" onsubmit="return confirm('Berhentikan wewenang Waka SDM dan kembalikan menjadi Guru biasa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="h-7 px-2.5 border border-rose-200 dark:border-rose-900 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/60 text-rose-700 dark:text-rose-300 rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                                                Berhentikan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="py-5 text-center text-slate-400 dark:text-slate-500 italic text-xs">Belum ada Waka SDM / Kepegawaian yang ditugaskan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
