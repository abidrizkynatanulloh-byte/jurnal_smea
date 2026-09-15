@forelse ($guruList as $idx => $g)
    <tr class="hover:bg-slate-50/80 transition-colors guru-row">
        <td class="py-2 px-3.5 text-center font-medium text-slate-400 text-xs tabular-nums">
            {{ $guruList->firstItem() + $idx }}
        </td>
        <td class="py-2 px-3.5">
            <p class="font-semibold text-slate-900 text-xs leading-tight">{{ $g->nama_guru }}</p>
            <p class="text-[11px] text-slate-400 font-mono tabular-nums mt-0.5">NIP: {{ $g->nip }}</p>
        </td>
        <td class="py-2 px-3.5">
            <span class="inline-block px-2 py-0.5 rounded text-[11px] font-medium border
                @if($g->jabatan === 'Kepala Sekolah') bg-emerald-50 text-emerald-800 border-emerald-200
                @elseif(str_contains($g->jabatan, 'Wakasis')) bg-indigo-50 text-indigo-800 border-indigo-200
                @elseif($g->jabatan === 'Guru Piket') bg-amber-50 text-amber-800 border-amber-200
                @else bg-slate-100 text-slate-700 border-slate-200 @endif">
                {{ $g->jabatan ?? 'Guru' }}
            </span>
        </td>
        <td class="py-2 px-3.5 text-xs text-slate-600 font-mono tabular-nums">
            {{ $g->no_hp ?: '-' }}
        </td>
        <td class="py-2 px-3.5 text-center">
            <div class="flex items-center justify-center space-x-1">
                <button type="button" 
                    onclick="openEditGuruModal('{{ $g->id_guru }}', '{{ addslashes($g->nama_guru) }}', '{{ $g->nip }}', '{{ $g->no_hp ?? '' }}', '{{ $g->user ? $g->user->role : 'guru' }}')"
                    class="w-6.5 h-6.5 rounded border border-slate-200 hover:border-slate-300 hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Edit Guru">
                    <i data-lucide="edit-2" class="w-3 h-3"></i>
                </button>
                <button type="button" 
                    onclick="openDeleteGuruModal('{{ $g->id_guru }}', '{{ addslashes($g->nama_guru) }}')"
                    class="w-6.5 h-6.5 rounded border border-slate-200 hover:border-rose-200 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Hapus Guru">
                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="py-12 text-center text-slate-400 italic text-xs">
            <i data-lucide="inbox" class="w-7 h-7 mx-auto mb-1.5 text-slate-300"></i>
            Tidak ada data guru yang sesuai.
        </td>
    </tr>
@endforelse
