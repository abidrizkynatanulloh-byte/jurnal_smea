@forelse ($mapelList as $m)
    <tr class="hover:bg-slate-50/80 transition-colors mapel-row">
        <td class="py-2 px-3.5 font-semibold text-slate-900 font-mono text-xs tabular-nums">{{ $m->kode_mapel }}</td>
        <td class="py-2 px-3.5 font-medium text-slate-900 text-xs">{{ $m->nama_mapel }}</td>
        <td class="py-2 px-3.5 text-center">
            <div class="flex items-center justify-center space-x-1">
                <button type="button" 
                    onclick="openEditMapelModal('{{ $m->kode_mapel }}', '{{ addslashes($m->nama_mapel) }}')"
                    class="w-6.5 h-6.5 rounded-md border border-slate-200 hover:border-slate-300 hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Edit Mapel">
                    <i data-lucide="edit-2" class="w-3 h-3"></i>
                </button>
                <form action="{{ route('admin.mapel.destroy', $m->kode_mapel) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mapel {{ addslashes($m->nama_mapel) }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-6.5 h-6.5 rounded-md border border-slate-200 hover:border-rose-200 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Hapus Mapel">
                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="3" class="py-12 text-center text-slate-400 italic text-xs">
            <i data-lucide="inbox" class="w-7 h-7 mx-auto mb-1.5 text-slate-300"></i>
            Tidak ada data mata pelajaran.
        </td>
    </tr>
@endforelse
