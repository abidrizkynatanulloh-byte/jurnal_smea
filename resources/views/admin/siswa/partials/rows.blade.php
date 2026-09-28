@forelse ($siswaList as $idx => $s)
    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors siswa-row" data-search="{{ strtolower($s->nama_siswa . ' ' . $s->nis . ' ' . $s->nisn . ' ' . ($s->kelas ? $s->kelas->nama_kelas : '')) }}">
        <!-- NO -->
        <td class="py-2.5 px-3 text-center font-medium text-slate-400 text-xs tabular-nums">
            {{ $siswaList->firstItem() + $idx }}
        </td>

        <!-- NISN -->
        <td class="py-2.5 px-3 text-xs text-slate-600 dark:text-slate-300 font-mono tabular-nums font-semibold">
            {{ $s->nisn ?: '-' }}
        </td>

        <!-- NAMA LENGKAP SISWA -->
        <td class="py-2.5 px-3 font-medium text-slate-900 dark:text-slate-100 text-xs leading-tight">
            {{ $s->nama_siswa }}
        </td>

        <!-- GENDER -->
        <td class="py-2.5 px-2 text-center">
            @if($s->jenis_kelamin === 'P')
                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-pink-50 dark:bg-pink-950/40 text-pink-700 dark:text-pink-400 border border-pink-200 dark:border-pink-800/50" title="Perempuan">P</span>
            @else
                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800/50" title="Laki-laki">L</span>
            @endif
        </td>

        <!-- AKSI -->
        <td class="py-2.5 px-3 text-center">
            <div class="flex items-center justify-center space-x-1.5">
                <!-- Tombol Detail -->
                <button type="button" 
                    onclick="openDetailModal('{{ $s->nisn }}', '{{ addslashes($s->nama_siswa) }}', '{{ $s->nis ?: '-' }}', '{{ $s->kelas ? addslashes($s->kelas->nama_kelas) : '-' }}', '{{ $s->jenis_kelamin === 'P' ? 'Perempuan' : 'Laki-laki' }}', '{{ $s->no_hp_wali ?: '-' }}', '{{ addslashes($s->kota_lahir ?: '') }}', '{{ $s->tanggal_lahir ? \Carbon\Carbon::parse($s->tanggal_lahir)->format('d/m/Y') : '-' }}')"
                    class="w-7 h-7 rounded-lg border border-slate-200 dark:border-slate-700 hover:border-blue-300 hover:bg-blue-50 dark:hover:bg-blue-950/40 text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Lihat Detail Siswa">
                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                </button>

                <!-- Tombol Edit -->
                <button type="button" 
                    onclick="openEditModal('{{ $s->nisn }}', '{{ addslashes($s->nama_siswa) }}', '{{ $s->nisn }}', '{{ $s->id_kelas }}', '{{ $s->jenis_kelamin ?? 'L' }}', '{{ $s->no_hp_wali ?? '' }}', '{{ $s->nis ?? '' }}', '{{ addslashes($s->kota_lahir ?? '') }}', '{{ $s->tanggal_lahir ?? '' }}')"
                    class="w-7 h-7 rounded-lg border border-slate-200 dark:border-slate-700 hover:border-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Edit Siswa">
                    <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                </button>

                <!-- Tombol Hapus -->
                <button type="button" 
                    onclick="openDeleteSiswaModal('{{ $s->nisn }}', '{{ addslashes($s->nama_siswa) }}')"
                    class="w-7 h-7 rounded-lg border border-slate-200 dark:border-slate-700 hover:border-rose-200 hover:bg-rose-50 dark:hover:bg-rose-950/40 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Pindahkan ke Sampah">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="py-12 text-center text-slate-400 italic text-xs">
            <i data-lucide="inbox" class="w-7 h-7 mx-auto mb-1.5 text-slate-300 dark:text-slate-600"></i>
            Tidak ada data siswa yang sesuai.
        </td>
    </tr>
@endforelse
