@extends('layouts.app')

@section('title', 'Event Sekolah & Pulang Pagi - Jurnal Esemkita')

@section('content')
<div class="flex-1 flex flex-col min-h-0 space-y-2.5">

    {{-- Header --}}
    <div class="shrink-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div class="flex items-center space-x-2.5">
            <h1 class="text-lg font-bold text-slate-900 tracking-tight">Event Sekolah & Pulang Pagi</h1>
            <span class="px-2 py-0.5 text-[11px] font-semibold bg-slate-100 text-slate-700 rounded-md font-mono tabular-nums border border-slate-200">
                {{ $daftarEvent->count() }} Event
            </span>
        </div>
        <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
            class="h-8 px-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer">
            <i data-lucide="panel-left-close" class="w-3.5 h-3.5 text-slate-500"></i>
            <span id="foldText">Sembunyikan Form Tambah</span>
        </button>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="shrink-0 flex items-center space-x-2.5 px-3.5 py-2.5 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-xs font-semibold">
            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="shrink-0 px-3.5 py-2.5 bg-rose-50 border border-rose-200 rounded-xl text-rose-800 text-xs space-y-0.5">
            @foreach ($errors->all() as $err)
                <div class="flex items-center space-x-2"><i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i><span>{{ $err }}</span></div>
            @endforeach
        </div>
    @endif

    {{-- Filter Bulan --}}
    <form method="GET" action="{{ route('admin.event.index') }}" class="shrink-0 flex items-center gap-2 flex-wrap">
        <select name="bulan" class="h-8 px-2.5 border border-slate-200 rounded-lg text-xs bg-white text-slate-800 focus:outline-none focus:border-slate-400 cursor-pointer">
            @foreach($namaBulanList as $num => $nama)
                <option value="{{ $num }}" @selected($bulan == $num)>{{ $nama }}</option>
            @endforeach
        </select>
        <input type="number" name="tahun" value="{{ $tahun }}" min="2020" max="2099"
            class="h-8 w-24 px-2.5 border border-slate-200 rounded-lg text-xs bg-white text-slate-800 font-mono focus:outline-none focus:border-slate-400">
        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama event..."
            class="h-8 px-2.5 border border-slate-200 rounded-lg text-xs bg-white text-slate-800 focus:outline-none focus:border-slate-400 w-44">
        <button type="submit" class="h-8 px-3 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 cursor-pointer">
            <i data-lucide="filter" class="w-3.5 h-3.5"></i>
            <span>Filter</span>
        </button>
        <a href="{{ route('admin.event.index') }}" class="h-8 px-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-lg text-xs font-medium transition-colors flex items-center cursor-pointer">
            Reset
        </a>
    </form>

    {{-- Main Grid --}}
    <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-3 gap-3.5 items-start" id="masterDataGrid">

        {{-- FORM TAMBAH --}}
        <div id="formPanel" class="bg-white border border-slate-200/90 rounded-xl p-3.5 shadow-2xs space-y-2.5">
            <div class="flex items-center space-x-2 pb-2 border-b border-slate-100 shrink-0">
                <div class="w-5 h-5 rounded-md bg-[#1E2538] text-white flex items-center justify-center">
                    <i data-lucide="calendar-plus" class="w-3 h-3"></i>
                </div>
                <span class="font-bold text-xs text-slate-900 tracking-tight uppercase">Tambah Event Baru</span>
            </div>

            <form action="{{ route('admin.event.store') }}" method="POST" class="space-y-2.5" id="formTambah">
                @csrf

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Nama Event *</label>
                    <input type="text" name="nama_event" placeholder="Cth: Classmeet, Dies Natalis, Libur Semester" required
                        class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Jenis *</label>
                    <select name="jenis" id="jenisSelect" onchange="toggleJamPulang(this.value)" required
                        class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                        <option value="event">🎉 Event / Libur (Semua guru bebas seharian)</option>
                        <option value="pulang_pagi">🔔 Pulang Pagi (Guru setelah jam pulang bebas)</option>
                    </select>
                </div>

                {{-- Jam Pulang - hanya muncul jika jenis = pulang_pagi --}}
                <div id="wrapJamPulang" class="hidden">
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Jam Pulang *</label>
                    <input type="time" name="jam_pulang" id="jamPulangInput"
                        class="w-full h-8 px-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-mono focus:outline-none focus:border-slate-800 cursor-pointer">
                    <p class="text-[10px] text-slate-500 mt-0.5">Guru yang mengajar mulai jam ini atau setelahnya akan bebas jurnal.</p>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Tanggal Mulai *</label>
                        <input type="date" name="tanggal_mulai" required
                            class="w-full h-8 px-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-mono focus:outline-none focus:border-slate-800 cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Tanggal Selesai *</label>
                        <input type="date" name="tanggal_selesai" required
                            class="w-full h-8 px-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-mono focus:outline-none focus:border-slate-800 cursor-pointer">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Keterangan</label>
                    <textarea name="keterangan" rows="2" placeholder="Opsional. Misal: Semua kelas ikut classmeet olahraga."
                        class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors resize-none"></textarea>
                </div>

                <div class="pt-1">
                    <button type="submit"
                        class="w-full min-h-[40px] py-2 px-4 bg-[#1E2538] hover:bg-[#121724] text-white rounded-xl text-xs font-bold tracking-wide transition-all flex items-center justify-center space-x-2 shadow-sm hover:shadow-md cursor-pointer">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Tambah Event</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- TABEL DAFTAR EVENT --}}
        <div id="tableCol" class="lg:col-span-2 bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden">
            <div class="px-3.5 py-2.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <span class="font-bold text-slate-900 text-xs flex items-center space-x-2 uppercase tracking-tight">
                    <i data-lucide="calendar-days" class="w-3.5 h-3.5 text-slate-600"></i>
                    <span>Daftar Event & Pulang Pagi</span>
                </span>
                <span class="text-xs text-slate-500 font-mono font-medium">{{ $daftarEvent->count() }} data</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-white border-b border-slate-200 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                            <th class="py-2.5 px-3.5">NAMA EVENT</th>
                            <th class="py-2.5 px-3.5 text-center w-28">JENIS</th>
                            <th class="py-2.5 px-3.5">TANGGAL</th>
                            <th class="py-2.5 px-3.5 text-center w-20">JAM PULANG</th>
                            <th class="py-2.5 px-3.5 text-center w-16">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($daftarEvent as $ev)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-2.5 px-3.5">
                                    <div class="font-semibold text-slate-900 text-xs">{{ $ev->nama_event }}</div>
                                    @if ($ev->keterangan)
                                        <div class="text-[10px] text-slate-500 mt-0.5">{{ Str::limit($ev->keterangan, 60) }}</div>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3.5 text-center">
                                    @if ($ev->jenis === 'event')
                                        <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-violet-50 text-violet-700 border border-violet-200">
                                            🎉 Event
                                        </span>
                                    @else
                                        <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            🔔 Pulang Pagi
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3.5">
                                    <div class="font-mono text-xs text-slate-800">
                                        {{ \Carbon\Carbon::parse($ev->tanggal_mulai)->translatedFormat('d M Y') }}
                                        @if ($ev->tanggal_mulai->toDateString() !== $ev->tanggal_selesai->toDateString())
                                            <span class="text-slate-400 mx-1">s/d</span>
                                            {{ \Carbon\Carbon::parse($ev->tanggal_selesai)->translatedFormat('d M Y') }}
                                            <span class="ml-1 text-[10px] text-slate-400">({{ $ev->durasi_hari }} hari)</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-2.5 px-3.5 text-center font-mono text-xs">
                                    @if ($ev->jam_pulang)
                                        <span class="font-semibold text-amber-700">{{ substr($ev->jam_pulang, 0, 5) }} WIB</span>
                                    @else
                                        <span class="text-slate-400 italic text-[10px]">Seharian</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3.5 text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <button type="button"
                                            onclick="openEditModal(
                                                {{ $ev->id }},
                                                '{{ addslashes($ev->nama_event) }}',
                                                '{{ $ev->jenis }}',
                                                '{{ $ev->tanggal_mulai->toDateString() }}',
                                                '{{ $ev->tanggal_selesai->toDateString() }}',
                                                '{{ $ev->jam_pulang ? substr($ev->jam_pulang, 0, 5) : '' }}',
                                                '{{ addslashes($ev->keterangan ?? '') }}'
                                            )"
                                            class="w-6.5 h-6.5 rounded-md border border-slate-200 hover:border-slate-300 hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Edit">
                                            <i data-lucide="edit-2" class="w-3 h-3"></i>
                                        </button>
                                        <form action="{{ route('admin.event.destroy', $ev->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus event \"{{ addslashes($ev->nama_event) }}\"?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-6.5 h-6.5 rounded-md border border-slate-200 hover:border-rose-200 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Hapus">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-slate-400 italic text-xs">
                                    <div class="flex flex-col items-center space-y-2">
                                        <i data-lucide="calendar-x" class="w-8 h-8 text-slate-300"></i>
                                        <span>Belum ada event yang tercatat untuk periode ini.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="modalEdit" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white border border-slate-200 rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <span class="font-bold text-slate-900 text-sm uppercase tracking-tight flex items-center space-x-2">
                <div class="w-7 h-7 rounded-lg bg-[#1E2538] text-white flex items-center justify-center">
                    <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                </div>
                <span>Edit Event Sekolah</span>
            </span>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="formEdit" method="POST" class="space-y-3.5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Event *</label>
                <input type="text" name="nama_event" id="edit_nama_event" required
                    class="w-full h-10 px-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-slate-800 transition-colors">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis *</label>
                <select name="jenis" id="edit_jenis" onchange="toggleEditJamPulang(this.value)" required
                    class="w-full h-10 px-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                    <option value="event">🎉 Event / Libur (Semua guru bebas seharian)</option>
                    <option value="pulang_pagi">🔔 Pulang Pagi (Guru setelah jam pulang bebas)</option>
                </select>
            </div>

            <div id="wrapEditJamPulang" class="hidden">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Jam Pulang *</label>
                <input type="time" name="jam_pulang" id="edit_jam_pulang"
                    class="w-full h-10 px-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 font-mono focus:outline-none focus:border-slate-800 cursor-pointer">
                <p class="text-[11px] text-slate-500 mt-0.5">Guru yang mengajar mulai jam ini atau setelahnya akan bebas jurnal.</p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Mulai *</label>
                    <input type="date" name="tanggal_mulai" id="edit_tanggal_mulai" required
                        class="w-full h-10 px-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 font-mono focus:outline-none focus:border-slate-800 cursor-pointer">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Selesai *</label>
                    <input type="date" name="tanggal_selesai" id="edit_tanggal_selesai" required
                        class="w-full h-10 px-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 font-mono focus:outline-none focus:border-slate-800 cursor-pointer">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Keterangan</label>
                <textarea name="keterangan" id="edit_keterangan" rows="2"
                    class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-slate-800 transition-colors resize-none"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()"
                    class="h-11 px-6 min-w-[100px] border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="h-11 px-7 min-w-[160px] bg-[#1E2538] hover:bg-[#121724] text-white rounded-xl text-sm font-bold transition-all cursor-pointer shadow-xs hover:shadow-md flex items-center justify-center space-x-2">
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let isFolded = false;

    function toggleFormPanel() {
        const formPanel = document.getElementById('formPanel');
        const tableCol  = document.getElementById('tableCol');
        const foldText  = document.getElementById('foldText');
        if (!isFolded) {
            formPanel.style.display = 'none';
            tableCol.className = 'lg:col-span-3 bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden';
            foldText.innerText = 'Buka Form Tambah';
            isFolded = true;
        } else {
            formPanel.style.display = 'block';
            tableCol.className = 'lg:col-span-2 bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden';
            foldText.innerText = 'Sembunyikan Form Tambah';
            isFolded = false;
        }
    }

    function toggleJamPulang(val) {
        const wrap  = document.getElementById('wrapJamPulang');
        const input = document.getElementById('jamPulangInput');
        if (val === 'pulang_pagi') {
            wrap.classList.remove('hidden');
            input.required = true;
        } else {
            wrap.classList.add('hidden');
            input.required = false;
        }
    }

    function toggleEditJamPulang(val) {
        const wrap  = document.getElementById('wrapEditJamPulang');
        const input = document.getElementById('edit_jam_pulang');
        if (val === 'pulang_pagi') {
            wrap.classList.remove('hidden');
            input.required = true;
        } else {
            wrap.classList.add('hidden');
            input.required = false;
        }
    }

    function openEditModal(id, nama, jenis, tglMulai, tglSelesai, jamPulang, keterangan) {
        document.getElementById('edit_nama_event').value    = nama;
        document.getElementById('edit_jenis').value         = jenis;
        document.getElementById('edit_tanggal_mulai').value = tglMulai;
        document.getElementById('edit_tanggal_selesai').value = tglSelesai;
        document.getElementById('edit_keterangan').value    = keterangan;
        document.getElementById('edit_jam_pulang').value    = jamPulang;
        document.getElementById('formEdit').action          = `/admin/event-sekolah/${id}`;
        toggleEditJamPulang(jenis);
        document.getElementById('modalEdit').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modalEdit').classList.add('hidden');
    }
</script>
@endsection
