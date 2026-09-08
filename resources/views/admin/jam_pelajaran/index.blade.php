@extends('layouts.app')

@section('title', 'Master Jam Pelajaran - Jurnal Esemkita')

@section('content')
<div class="flex-1 flex flex-col min-h-0 space-y-2.5">
    <!-- Header Halaman (Sesuai Ukuran Gambar 1 - FIXED) -->
    <div class="shrink-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div class="flex items-center space-x-2.5">
            <h1 class="text-lg font-bold text-slate-900 tracking-tight">Master Jam Pelajaran</h1>
            <span class="px-2 py-0.5 text-[11px] font-semibold bg-slate-100 text-slate-700 rounded-md font-mono tabular-nums border border-slate-200">
                {{ count($jamReguler) + count($jamJumat) }} Sesi Jam
            </span>
        </div>
        <div>
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="h-8 px-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer">
                <i data-lucide="panel-left-close" class="w-3.5 h-3.5 text-slate-500"></i>
                <span id="foldText">Sembunyikan Form Tambah</span>
            </button>
        </div>
    </div>

    <!-- Layout Grid Utama (Full Viewport Height - Sesuai Gambar 1) -->
    <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-3 gap-3.5 items-stretch" id="masterDataGrid">
        
        <!-- BAGIAN 1: FORM TAMBAH JAM (UKURAN & PROPORSI SESUAI GAMBAR 1) -->
        <div id="formPanel" class="bg-white border border-slate-200/90 rounded-xl p-3.5 shadow-2xs space-y-2.5 overflow-y-auto h-full max-h-full">
            <div class="flex items-center space-x-2 pb-2 border-b border-slate-100 shrink-0">
                <div class="w-5 h-5 rounded-md bg-[#1E2538] text-white flex items-center justify-center">
                    <i data-lucide="clock" class="w-3 h-3"></i>
                </div>
                <span class="font-bold text-xs text-slate-900 tracking-tight uppercase">TAMBAH SESI JAM BARU</span>
            </div>
            
            <form action="{{ route('admin.jam.store') }}" method="POST" class="space-y-2">
                @csrf

                <div>
                    <label for="jam_ke" class="block text-[11px] font-semibold text-slate-700 mb-0.5">Sesi Jam Ke- *</label>
                    <input type="number" name="jam_ke" id="jam_ke" placeholder="Misal: 1" min="1" required 
                        class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-mono placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                </div>

                <div>
                    <label for="kelompok_hari" class="block text-[11px] font-semibold text-slate-700 mb-0.5">Kelompok Hari *</label>
                    <select name="kelompok_hari" id="kelompok_hari" required 
                        class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                        <option value="Reguler">Reguler (Senin - Kamis)</option>
                        <option value="Jumat">Jumat</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label for="waktu_mulai" class="block text-[11px] font-semibold text-slate-700 mb-0.5">Waktu Mulai *</label>
                        <input type="time" name="waktu_mulai" id="waktu_mulai" required
                            class="w-full h-8 px-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-mono focus:outline-none focus:border-slate-800 cursor-pointer">
                    </div>
                    <div>
                        <label for="waktu_selesai" class="block text-[11px] font-semibold text-slate-700 mb-0.5">Waktu Selesai *</label>
                        <input type="time" name="waktu_selesai" id="waktu_selesai" required
                            class="w-full h-8 px-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-mono focus:outline-none focus:border-slate-800 cursor-pointer">
                    </div>
                </div>

                <div class="pt-1.5">
                    <button type="submit" class="w-full h-10.5 bg-[#1E2538] hover:bg-[#121724] text-white rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center justify-center space-x-2 shadow-xs cursor-pointer">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Tambah Jam Pelajaran</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR SESI JAM PELAJARAN (SESUAI GAMBAR 1) -->
        <div id="tableCol" class="lg:col-span-2 flex flex-col h-full min-h-0 space-y-3 overflow-y-auto">
            
            <!-- TABLE REGULER -->
            <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden shrink-0">
                <div class="px-3.5 py-2 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <span class="font-bold text-slate-900 text-xs flex items-center space-x-2 uppercase tracking-tight">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-600"></i>
                        <span>Jadwal Reguler (Senin - Kamis)</span>
                    </span>
                    <span class="text-xs text-slate-500 font-mono font-medium">{{ count($jamReguler) }} Sesi</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-white border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                                <th class="py-2.5 px-3.5 text-center w-20">JAM KE-</th>
                                <th class="py-2.5 px-3.5">RENTANG WAKTU</th>
                                <th class="py-2.5 px-3.5 text-center w-28">STATUS</th>
                                <th class="py-2.5 px-3.5 text-center w-20">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($jamReguler as $j)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2 px-3.5 text-center font-bold text-slate-900 font-mono text-xs">{{ $j->jam_ke }}</td>
                                    <td class="py-2 px-3.5 font-medium text-slate-800 font-mono tabular-nums text-xs">
                                        {{ substr($j->waktu_mulai, 0, 5) }} - {{ substr($j->waktu_selesai, 0, 5) }} WIB
                                    </td>
                                    <td class="py-2 px-3.5 text-center">
                                        @if ($j->is_aktif)
                                            <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-3.5 text-center">
                                        <div class="flex items-center justify-center space-x-1">
                                            @if ($j->is_aktif)
                                                <form action="{{ route('admin.jam.nonaktifkan', $j->id_jam) }}" method="POST" class="inline" onsubmit="return confirm('Nonaktifkan jam ke-{{ $j->jam_ke }} ({{ $j->kelompok_hari }})?\nJam pelajaran setelahnya akan otomatis maju.')">
                                                    @csrf
                                                    <button type="submit" class="w-6.5 h-6.5 rounded-md border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-700 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Nonaktifkan Jam (Otomatis Majukan Jam Setelahnya)">
                                                        <i data-lucide="power" class="w-3 h-3"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.jam.aktifkan', $j->id_jam) }}" method="POST" class="inline" onsubmit="return confirm('Aktifkan kembali jam ke-{{ $j->jam_ke }} ({{ $j->kelompok_hari }})?\nJam pelajaran setelahnya akan otomatis mundur.')">
                                                    @csrf
                                                    <button type="submit" class="w-6.5 h-6.5 rounded-md border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Aktifkan Kembali Jam (Otomatis Mundurkan Jam Setelahnya)">
                                                        <i data-lucide="power" class="w-3 h-3"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <button type="button" 
                                                onclick="openEditJamModal('{{ $j->id_jam }}', '{{ $j->jam_ke }}', '{{ $j->kelompok_hari }}', '{{ substr($j->waktu_mulai, 0, 5) }}', '{{ substr($j->waktu_selesai, 0, 5) }}', '{{ $j->is_aktif }}')"
                                                class="w-6.5 h-6.5 rounded-md border border-slate-200 hover:border-slate-300 hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Edit Jam">
                                                <i data-lucide="edit-2" class="w-3 h-3"></i>
                                            </button>
                                            <form action="{{ route('admin.jam.destroy', $j->id_jam) }}" method="POST" onsubmit="return confirm('Hapus jam ke-{{ $j->jam_ke }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-6.5 h-6.5 rounded-md border border-slate-200 hover:border-rose-200 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Hapus Jam">
                                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @if ($j->jam_ke == 4)
                                    @php
                                        $jam5 = $jamReguler->firstWhere('jam_ke', 5);
                                        $waktuMulaiIst1 = substr($j->waktu_selesai, 0, 5);
                                        $waktuSelesaiIst1 = $jam5 ? substr($jam5->waktu_mulai, 0, 5) : \Carbon\Carbon::parse($j->waktu_selesai)->addMinutes(20)->format('H:i');
                                    @endphp
                                    <tr class="bg-amber-50/70 border-y border-amber-200/80 font-medium text-amber-900">
                                        <td class="py-1.5 px-3.5 text-center font-bold text-amber-700 text-xs">☕</td>
                                        <td class="py-1.5 px-3.5 font-bold font-mono tabular-nums text-xs text-amber-900">
                                            <span class="px-1.5 py-0.5 rounded bg-amber-100 border border-amber-300 text-amber-900 text-[10px] uppercase font-sans font-bold tracking-wide mr-2">
                                                ISTIRAHAT 1
                                            </span>
                                            {{ $waktuMulaiIst1 }} - {{ $waktuSelesaiIst1 }} WIB
                                        </td>
                                        <td class="py-1.5 px-3.5 text-center">
                                            <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-100 text-amber-800 border border-amber-300">
                                                20 Menit
                                            </span>
                                        </td>
                                        <td class="py-1.5 px-3.5 text-center text-slate-400 text-xs italic">-</td>
                                    </tr>
                                @elseif ($j->jam_ke == 7)
                                    @php
                                        $jam8 = $jamReguler->firstWhere('jam_ke', 8);
                                        $waktuMulaiIst2 = substr($j->waktu_selesai, 0, 5);
                                        $waktuSelesaiIst2 = $jam8 ? substr($jam8->waktu_mulai, 0, 5) : \Carbon\Carbon::parse($j->waktu_selesai)->addMinutes(90)->format('H:i');
                                    @endphp
                                    <tr class="bg-indigo-50/70 border-y border-indigo-200/80 font-medium text-indigo-900">
                                        <td class="py-1.5 px-3.5 text-center font-bold text-indigo-700 text-xs">🍽️</td>
                                        <td class="py-1.5 px-3.5 font-bold font-mono tabular-nums text-xs text-indigo-900">
                                            <span class="px-1.5 py-0.5 rounded bg-indigo-100 border border-indigo-300 text-indigo-900 text-[10px] uppercase font-sans font-bold tracking-wide mr-2">
                                                ISTIRAHAT 2 / ISHOMA
                                            </span>
                                            {{ $waktuMulaiIst2 }} - {{ $waktuSelesaiIst2 }} WIB
                                        </td>
                                        <td class="py-1.5 px-3.5 text-center">
                                            <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-100 text-indigo-800 border border-indigo-300">
                                                90 Menit
                                            </span>
                                        </td>
                                        <td class="py-1.5 px-3.5 text-center text-slate-400 text-xs italic">-</td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-400 italic text-xs">
                                        Belum ada jadwal sesi reguler.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TABLE JUMAT -->
            <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden shrink-0">
                <div class="px-3.5 py-2 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <span class="font-bold text-slate-900 text-xs flex items-center space-x-2 uppercase tracking-tight">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-600"></i>
                        <span>Jadwal Khusus Hari Jumat</span>
                    </span>
                    <span class="text-xs text-slate-500 font-mono font-medium">{{ count($jamJumat) }} Sesi</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-white border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                                <th class="py-2.5 px-3.5 text-center w-20">JAM KE-</th>
                                <th class="py-2.5 px-3.5">RENTANG WAKTU</th>
                                <th class="py-2.5 px-3.5 text-center w-28">STATUS</th>
                                <th class="py-2.5 px-3.5 text-center w-20">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($jamJumat as $j)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2 px-3.5 text-center font-bold text-slate-900 font-mono text-xs">{{ $j->jam_ke }}</td>
                                    <td class="py-2 px-3.5 font-medium text-slate-800 font-mono tabular-nums text-xs">
                                        {{ substr($j->waktu_mulai, 0, 5) }} - {{ substr($j->waktu_selesai, 0, 5) }} WIB
                                    </td>
                                    <td class="py-2 px-3.5 text-center">
                                        @if ($j->is_aktif)
                                            <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-3.5 text-center">
                                        <div class="flex items-center justify-center space-x-1">
                                            @if ($j->is_aktif)
                                                <form action="{{ route('admin.jam.nonaktifkan', $j->id_jam) }}" method="POST" class="inline" onsubmit="return confirm('Nonaktifkan jam ke-{{ $j->jam_ke }} ({{ $j->kelompok_hari }})?\nJam pelajaran setelahnya akan otomatis maju.')">
                                                    @csrf
                                                    <button type="submit" class="w-6.5 h-6.5 rounded-md border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-700 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Nonaktifkan Jam (Otomatis Majukan Jam Setelahnya)">
                                                        <i data-lucide="power" class="w-3 h-3"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.jam.aktifkan', $j->id_jam) }}" method="POST" class="inline" onsubmit="return confirm('Aktifkan kembali jam ke-{{ $j->jam_ke }} ({{ $j->kelompok_hari }})?\nJam pelajaran setelahnya akan otomatis mundur.')">
                                                    @csrf
                                                    <button type="submit" class="w-6.5 h-6.5 rounded-md border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Aktifkan Kembali Jam (Otomatis Mundurkan Jam Setelahnya)">
                                                        <i data-lucide="power" class="w-3 h-3"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <button type="button" 
                                                onclick="openEditJamModal('{{ $j->id_jam }}', '{{ $j->jam_ke }}', '{{ $j->kelompok_hari }}', '{{ substr($j->waktu_mulai, 0, 5) }}', '{{ substr($j->waktu_selesai, 0, 5) }}', '{{ $j->is_aktif }}')"
                                                class="w-6.5 h-6.5 rounded-md border border-slate-200 hover:border-slate-300 hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Edit Jam">
                                                <i data-lucide="edit-2" class="w-3 h-3"></i>
                                            </button>
                                            <form action="{{ route('admin.jam.destroy', $j->id_jam) }}" method="POST" onsubmit="return confirm('Hapus jam ke-{{ $j->jam_ke }} Jumat?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-6.5 h-6.5 rounded-md border border-slate-200 hover:border-rose-200 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Hapus Jam">
                                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @if ($j->jam_ke == 5)
                                    @php
                                        $jam6 = $jamJumat->firstWhere('jam_ke', 6);
                                        $waktuMulaiIst1 = substr($j->waktu_selesai, 0, 5);
                                        $waktuSelesaiIst1 = $jam6 ? substr($jam6->waktu_mulai, 0, 5) : \Carbon\Carbon::parse($j->waktu_selesai)->addMinutes(20)->format('H:i');
                                    @endphp
                                    <tr class="bg-amber-50/70 border-y border-amber-200/80 font-medium text-amber-900">
                                        <td class="py-1.5 px-3.5 text-center font-bold text-amber-700 text-xs">☕</td>
                                        <td class="py-1.5 px-3.5 font-bold font-mono tabular-nums text-xs text-amber-900">
                                            <span class="px-1.5 py-0.5 rounded bg-amber-100 border border-amber-300 text-amber-900 text-[10px] uppercase font-sans font-bold tracking-wide mr-2">
                                                ISTIRAHAT 1
                                            </span>
                                            {{ $waktuMulaiIst1 }} - {{ $waktuSelesaiIst1 }} WIB
                                        </td>
                                        <td class="py-1.5 px-3.5 text-center">
                                            <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-100 text-amber-800 border border-amber-300">
                                                20 Menit
                                            </span>
                                        </td>
                                        <td class="py-1.5 px-3.5 text-center text-slate-400 text-xs italic">-</td>
                                    </tr>
                                @elseif ($j->jam_ke == 8)
                                    @php
                                        $jam9 = $jamJumat->firstWhere('jam_ke', 9);
                                        $waktuMulaiIst2 = substr($j->waktu_selesai, 0, 5);
                                        $waktuSelesaiIst2 = $jam9 ? substr($jam9->waktu_mulai, 0, 5) : \Carbon\Carbon::parse($j->waktu_selesai)->addMinutes(100)->format('H:i');
                                    @endphp
                                    <tr class="bg-indigo-50/70 border-y border-indigo-200/80 font-medium text-indigo-900">
                                        <td class="py-1.5 px-3.5 text-center font-bold text-indigo-700 text-xs">🕌</td>
                                        <td class="py-1.5 px-3.5 font-bold font-mono tabular-nums text-xs text-indigo-900">
                                            <span class="px-1.5 py-0.5 rounded bg-indigo-100 border border-indigo-300 text-indigo-900 text-[10px] uppercase font-sans font-bold tracking-wide mr-2">
                                                ISTIRAHAT 2 / JUMATAN
                                            </span>
                                            {{ $waktuMulaiIst2 }} - {{ $waktuSelesaiIst2 }} WIB
                                        </td>
                                        <td class="py-1.5 px-3.5 text-center">
                                            <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-100 text-indigo-800 border border-indigo-300">
                                                100 Menit
                                            </span>
                                        </td>
                                        <td class="py-1.5 px-3.5 text-center text-slate-400 text-xs italic">-</td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-400 italic text-xs">
                                        Belum ada jadwal sesi Jumat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- MODAL EDIT JAM PELAJARAN (Proporsional & Lebih Lega Sesuai Permintaan) -->
<div id="modalEditJam" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white dark:bg-[#151B26] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl max-w-lg w-full p-6 sm:p-7 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
            <span class="font-bold text-slate-900 dark:text-slate-100 text-sm uppercase tracking-tight flex items-center space-x-2">
                <div class="w-7 h-7 rounded-lg bg-[#1E2538] text-white flex items-center justify-center">
                    <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                </div>
                <span>Edit Sesi Jam Pelajaran</span>
            </span>
            <button type="button" onclick="closeEditJamModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="formEditJam" method="POST" class="space-y-3.5">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_jam_ke" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Jam Ke- *</label>
                <input type="number" name="jam_ke" id="edit_jam_ke" required min="1"
                    class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 font-mono focus:outline-none focus:border-slate-800 transition-colors">
            </div>

            <div>
                <label for="edit_kelompok_hari" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kelompok Hari *</label>
                <select name="kelompok_hari" id="edit_kelompok_hari" required 
                    class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                    <option value="Reguler">Reguler (Senin - Kamis)</option>
                    <option value="Jumat">Jumat</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit_waktu_mulai" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Waktu Mulai *</label>
                    <input type="time" name="waktu_mulai" id="edit_waktu_mulai" required
                        class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 font-mono focus:outline-none focus:border-slate-800">
                </div>
                <div>
                    <label for="edit_waktu_selesai" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Waktu Selesai *</label>
                    <input type="time" name="waktu_selesai" id="edit_waktu_selesai" required
                        class="w-full h-10 px-3 bg-white dark:bg-[#1A2230] border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 font-mono focus:outline-none focus:border-slate-800">
                </div>
            </div>

            <div class="flex items-center space-x-2.5 pt-1">
                <input type="checkbox" name="is_aktif" id="edit_is_aktif" value="1" class="w-4 h-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900 cursor-pointer">
                <label for="edit_is_aktif" class="text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer select-none">Sesi Aktif Digunakan</label>
            </div>

            <div class="flex items-center justify-end space-x-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="closeEditJamModal()" class="h-10 px-4.5 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-10 px-5.5 bg-[#1E2538] hover:bg-[#121724] text-white rounded-xl text-xs sm:text-sm font-bold transition-colors cursor-pointer shadow-xs">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let isFolded = false;
    function toggleFormPanel() {
        const formPanel = document.getElementById('formPanel');
        const tableCol = document.getElementById('tableCol');
        const foldText = document.getElementById('foldText');

        if (!isFolded) {
            formPanel.style.display = 'none';
            tableCol.className = 'lg:col-span-3 flex flex-col h-full min-h-0 space-y-3 overflow-y-auto';
            foldText.innerText = 'Buka Form Tambah';
            isFolded = true;
        } else {
            formPanel.style.display = 'block';
            tableCol.className = 'lg:col-span-2 flex flex-col h-full min-h-0 space-y-3 overflow-y-auto';
            foldText.innerText = 'Sembunyikan Form Tambah';
            isFolded = false;
        }
    }

    function openEditJamModal(id, jamKe, hari, mulai, selesai, aktif) {
        document.getElementById('edit_jam_ke').value = jamKe;
        document.getElementById('edit_kelompok_hari').value = hari;
        document.getElementById('edit_waktu_mulai').value = mulai;
        document.getElementById('edit_waktu_selesai').value = selesai;
        document.getElementById('edit_is_aktif').checked = (aktif == 1 || aktif == true);

        document.getElementById('formEditJam').action = `/admin/jam-pelajaran/${id}`;
        document.getElementById('modalEditJam').classList.remove('hidden');
    }

    function closeEditJamModal() {
        document.getElementById('modalEditJam').classList.add('hidden');
    }
</script>
@endsection