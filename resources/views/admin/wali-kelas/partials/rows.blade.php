@forelse ($kelasList as $idx => $k)
    @php
        $waliGuru = $k->wali_kelas_guru_data;
    @endphp
    <tr class="hover:bg-slate-50/80 transition-colors">
        <td class="py-2.5 px-3.5 text-center font-medium text-slate-400 text-xs tabular-nums">
            {{ $kelasList->firstItem() + $idx }}
        </td>
        <td class="py-2.5 px-3.5">
            <span class="font-bold text-slate-900 text-xs tracking-tight">{{ $k->nama_kelas }}</span>
            <span class="ml-1.5 inline-block px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                {{ $k->jumlah_siswa ?? 36 }} Siswa
            </span>
        </td>
        <td class="py-2.5 px-3.5">
            @if($waliGuru)
                <div class="flex items-center space-x-2">
                    <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-[11px]">
                        {{ strtoupper(substr($waliGuru->nama_guru, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900 text-xs leading-tight">{{ $waliGuru->nama_guru }}</p>
                        <p class="text-[10px] text-slate-400 font-mono">NIP: {{ $waliGuru->nip }}</p>
                    </div>
                </div>
            @else
                <span class="inline-flex items-center space-x-1 text-slate-400 italic text-xs">
                    <i data-lucide="alert-circle" class="w-3.5 h-3.5 text-amber-400"></i>
                    <span>Belum Ada Wali Kelas</span>
                </span>
            @endif
        </td>
        <td class="py-2.5 px-3.5 text-right">
            <form action="{{ route('admin.wali-kelas.update', $k->id_kelas) }}" method="POST" class="flex items-center justify-end space-x-1.5">
                @csrf
                @method('PUT')
                <select name="id_guru" placeholder="-- Cari / Pilih Wali Kelas --" class="select-wali-kelas h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 font-medium focus:outline-none focus:border-slate-800 transition-colors w-60">
                    <option value="">-- Pilih Wali Kelas --</option>
                    @foreach($guruList as $g)
                        <option value="{{ $g->id_guru }}" {{ ($waliGuru && $waliGuru->id_guru == $g->id_guru) ? 'selected' : '' }}>
                            {{ $g->nama_guru }} (NIP: {{ $g->nip }})
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="h-8 px-3 bg-[#1E2538] hover:bg-[#121724] text-white rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1 shadow-2xs cursor-pointer shrink-0" title="Simpan Wali Kelas">
                    <i data-lucide="save" class="w-3.5 h-3.5"></i>
                    <span>Simpan</span>
                </button>
                @if($waliGuru)
                    <button type="button" onclick="confirmResetWaliKelas('{{ $k->id_kelas }}', '{{ addslashes($k->nama_kelas) }}')" class="h-8 w-8 bg-rose-50 border border-rose-200 hover:bg-rose-100 text-rose-600 rounded-lg flex items-center justify-center transition-colors cursor-pointer shrink-0" title="Kosongkan Wali Kelas">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                    </button>
                @endif
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="py-12 text-center text-slate-400 italic text-xs">
            <i data-lucide="inbox" class="w-7 h-7 mx-auto mb-1.5 text-slate-300"></i>
            Tidak ada data kelas yang sesuai.
        </td>
    </tr>
@endforelse
