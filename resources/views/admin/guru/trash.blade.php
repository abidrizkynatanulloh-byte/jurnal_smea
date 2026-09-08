@extends('layouts.app')

@section('title', 'Tong Sampah Data Guru & Pegawai - Jurnal Esemkita')

@section('content')
<div class="flex-1 flex flex-col min-h-0 space-y-2.5">
    <!-- Header Halaman -->
    <div class="shrink-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('admin.guru.index') }}" class="h-8 px-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Data Guru</span>
            </a>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight">Tong Sampah Guru & Pegawai</h1>
            <span class="px-2 py-0.5 text-[11px] font-semibold bg-rose-50 text-rose-700 rounded-md font-mono tabular-nums border border-rose-200">
                {{ $trashList->total() }} Dihapus
            </span>
        </div>
    </div>

    <!-- Alert Success -->
    @if (session('success'))
        <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-xs font-medium flex items-center space-x-2 shadow-2xs">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Data Table Card -->
    <div class="flex-1 min-h-0 bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden flex flex-col">
        <div class="flex-1 overflow-y-auto overflow-x-auto min-h-0">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="sticky top-0 bg-white border-b border-slate-200 z-10 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-2.5 px-3.5 text-center w-12 bg-white">NO</th>
                        <th class="py-2.5 px-3.5 bg-white">NAMA LENGKAP & NIP</th>
                        <th class="py-2.5 px-3.5 w-36 bg-white">JABATAN / ROLE</th>
                        <th class="py-2.5 px-3.5 bg-white">ALASAN DIHAPUS</th>
                        <th class="py-2.5 px-3.5 w-36 bg-white">TANGGAL DIHAPUS</th>
                        <th class="py-2.5 px-3.5 text-center w-28 bg-white">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($trashList as $idx => $g)
                        <tr class="hover:bg-slate-50/80 transition-colors cursor-pointer" onclick="showGuruDetailModal('{{ addslashes($g->nama_guru) }}', '{{ $g->nip ?: '-' }}', '{{ $g->jabatan ?: 'Guru' }}', '{{ $g->no_hp ?: '-' }}', '{{ addslashes($g->alasan_hapus ?: 'Tidak ada alasan dicatat') }}', '{{ $g->deleted_at ? $g->deleted_at->translatedFormat('d M Y, H:i') : '-' }}')">
                            <td class="py-2 px-3.5 text-center font-medium text-slate-400 text-xs tabular-nums">
                                {{ $trashList->firstItem() + $idx }}
                            </td>
                            <td class="py-2 px-3.5">
                                <p class="font-semibold text-slate-900 text-xs leading-tight">{{ $g->nama_guru }}</p>
                                <p class="text-[11px] text-slate-400 font-mono tabular-nums mt-0.5">NIP: {{ $g->nip ?: '-' }}</p>
                            </td>
                            <td class="py-2 px-3.5">
                                <span class="inline-block px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $g->jabatan ?: 'Guru' }}
                                </span>
                            </td>
                            <td class="py-2 px-3.5">
                                <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/80">
                                    <i data-lucide="info" class="w-3 h-3 shrink-0 text-rose-500"></i>
                                    <span class="truncate max-w-[200px]">{{ $g->alasan_hapus ?: 'Tidak ada alasan dicatat' }}</span>
                                </span>
                            </td>
                            <td class="py-2 px-3.5 text-xs text-slate-500 font-mono tabular-nums">
                                {{ $g->deleted_at ? $g->deleted_at->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-2 px-3.5 text-center" onclick="event.stopPropagation()">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <button type="button" onclick="showGuruDetailModal('{{ addslashes($g->nama_guru) }}', '{{ $g->nip ?: '-' }}', '{{ $g->jabatan ?: 'Guru' }}', '{{ $g->no_hp ?: '-' }}', '{{ addslashes($g->alasan_hapus ?: 'Tidak ada alasan dicatat') }}', '{{ $g->deleted_at ? $g->deleted_at->translatedFormat('d M Y, H:i') : '-' }}')" class="h-7 px-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-lg text-xs font-semibold transition-colors inline-flex items-center space-x-1 cursor-pointer" title="Lihat Detail Alasan">
                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                    </button>
                                    <form action="{{ route('admin.guru.restore', $g->id_guru) }}" method="POST" onsubmit="return confirm('Pulihkan data guru {{ addslashes($g->nama_guru) }}?')">
                                        @csrf
                                        <button type="submit" class="h-7 px-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-semibold transition-colors inline-flex items-center space-x-1 cursor-pointer" title="Pulihkan Data Guru">
                                            <i data-lucide="rotate-ccw" class="w-3 h-3"></i>
                                            <span>Pulihkan</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 italic text-xs">
                                <i data-lucide="inbox" class="w-7 h-7 mx-auto mb-1.5 text-slate-300"></i>
                                Tong sampah kosong. Tidak ada data guru yang dihapus.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="shrink-0">
            <x-pagination-bar :paginator="$trashList" />
        </div>
    </div>
</div>

<!-- MODAL DETAIL ALASAN HAPUS GURU -->
<div id="modalDetailGuruTrash" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white dark:bg-[#151B26] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center space-x-2.5">
                <div class="w-9 h-9 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 flex items-center justify-center shrink-0">
                    <i data-lucide="file-text" class="w-4.5 h-4.5 text-rose-600 dark:text-rose-400"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-slate-100 text-sm tracking-tight">Detail Alasan Penghapusan</h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Informasi data guru yang telah dihapus</p>
                </div>
            </div>
            <button type="button" onclick="closeGuruDetailModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="space-y-3 text-xs">
            <div class="p-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/60 rounded-xl space-y-1.5">
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 font-medium">Nama Guru / Pegawai:</span>
                    <span class="font-bold text-slate-900 dark:text-slate-100 text-sm" id="detailGuruNama"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 font-medium">NIP / NIK:</span>
                    <span class="font-mono text-slate-700 dark:text-slate-300" id="detailGuruNip"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 font-medium">Jabatan / Role:</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-300" id="detailGuruJabatan"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 font-medium">No. HP (WhatsApp):</span>
                    <span class="font-mono text-slate-700 dark:text-slate-300" id="detailGuruNoHp"></span>
                </div>
                <div class="flex justify-between items-center pt-1 border-t border-slate-200/60 dark:border-slate-700/40">
                    <span class="text-slate-500 font-medium">Waktu Dihapus:</span>
                    <span class="font-mono text-slate-600 dark:text-slate-400" id="detailGuruDeletedAt"></span>
                </div>
            </div>

            <div class="p-3.5 bg-rose-50/80 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/50 rounded-xl space-y-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-rose-700 dark:text-rose-400 block">Alasan Penghapusan:</span>
                <p class="text-xs text-rose-900 dark:text-rose-200 font-semibold leading-relaxed" id="detailGuruAlasan"></p>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="button" onclick="closeGuruDetailModal()" class="h-10 px-6 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white rounded-xl text-xs font-bold transition-all cursor-pointer shadow-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function showGuruDetailModal(nama, nip, jabatan, noHp, alasan, deletedAt) {
        document.getElementById('detailGuruNama').innerText = nama;
        document.getElementById('detailGuruNip').innerText = nip;
        document.getElementById('detailGuruJabatan').innerText = jabatan;
        document.getElementById('detailGuruNoHp').innerText = noHp;
        document.getElementById('detailGuruAlasan').innerText = alasan;
        document.getElementById('detailGuruDeletedAt').innerText = deletedAt;
        document.getElementById('modalDetailGuruTrash').classList.remove('hidden');
    }

    function closeGuruDetailModal() {
        document.getElementById('modalDetailGuruTrash').classList.add('hidden');
    }
</script>
@endsection
