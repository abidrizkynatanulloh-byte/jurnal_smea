@forelse ($siswaList as $idx => $s)
    <tr class="hover:bg-slate-50/80 transition-colors siswa-row" data-search="{{ strtolower($s->nama_siswa . ' ' . $s->nis . ' ' . $s->nisn . ' ' . ($s->kelas ? $s->kelas->nama_kelas : '')) }}">
        <td class="py-2 px-3 text-center font-medium text-slate-400 text-xs tabular-nums">
            {{ $siswaList->firstItem() + $idx }}
        </td>
        <td class="py-2 px-3 font-semibold text-slate-900 text-xs font-mono tabular-nums">{{ $s->nis }}</td>
        <td class="py-2 px-3 text-xs text-slate-500 font-mono tabular-nums">{{ $s->nisn }}</td>
        <td class="py-2 px-3 font-medium text-slate-900 text-xs leading-tight">{{ $s->nama_siswa }}</td>
        <td class="py-2 px-2 text-center">
            @if($s->jenis_kelamin === 'P')
                <span class="inline-flex items-center justify-center w-5.5 h-5.5 rounded-md text-[11px] font-bold bg-pink-50 text-pink-700 border border-pink-200" title="Perempuan">P</span>
            @else
                <span class="inline-flex items-center justify-center w-5.5 h-5.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200" title="Laki-laki">L</span>
            @endif
        </td>
        <td class="py-2 px-3">
            <span class="inline-block px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                {{ $s->kelas ? $s->kelas->nama_kelas : '-' }}
            </span>
        </td>
        <td class="py-2 px-3 text-xs text-slate-500 font-mono tabular-nums">
            {{ $s->no_hp_wali ?: '-' }}
        </td>
        <td class="py-2 px-3 text-center">
            <div class="flex items-center justify-center space-x-1">
                <button type="button" 
                    onclick="openEditModal('{{ $s->nis }}', '{{ addslashes($s->nama_siswa) }}', '{{ $s->nisn }}', '{{ $s->id_kelas }}', '{{ $s->jenis_kelamin ?? 'L' }}', '{{ $s->no_hp_wali ?? '' }}')"
                    class="w-6.5 h-6.5 rounded-md border border-slate-200 hover:border-slate-300 hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Edit Siswa">
                    <i data-lucide="edit-2" class="w-3 h-3"></i>
                </button>
                <button type="button" 
                    onclick="openDeleteSiswaModal('{{ $s->nis }}', '{{ addslashes($s->nama_siswa) }}')"
                    class="w-6.5 h-6.5 rounded-md border border-slate-200 hover:border-rose-200 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Pindahkan ke Sampah">
                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="py-12 text-center text-slate-400 italic text-xs">
            <i data-lucide="inbox" class="w-7 h-7 mx-auto mb-1.5 text-slate-300"></i>
            Tidak ada data siswa yang sesuai.
        </td>
    </tr>
@endforelse
