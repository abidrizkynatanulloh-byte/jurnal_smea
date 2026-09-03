@extends('layouts.app')

@section('title', 'Master Jam Pelajaran - Jurnal Esemkita')

@section('content')
<div class="space-y-5">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
        <div>
            <h1 class="text-xl font-extrabold text-[#1E2538] tracking-tight">Master Jam Pelajaran</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola sesi waktu dan periode jam pelajaran KBM harian</p>
        </div>
        <div class="flex items-center space-x-2.5">
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="px-3 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-[#1E2538] rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5 text-[#405078]"></i>
                <span id="foldText">Sembunyikan Form Tambah</span>
            </button>
        </div>
    </div>

    <!-- Alert / Notifikasi -->
    @if (session('success'))
        <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center space-x-2 shadow-2xs">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-semibold shadow-2xs">
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Responsive Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">
        
        <!-- BAGIAN 1: FORM TAMBAH JAM BARU (STICKY & FOLDABLE) -->
        <div id="formPanel" class="bg-white border border-slate-200 rounded-2xl p-4.5 shadow-xs space-y-3 lg:sticky lg:top-20 z-10 transition-all duration-300">
            <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-[#405078]"></i>
                    <h3 class="font-bold text-[#1E2538] text-xs uppercase tracking-wider">Tambah Sesi Jam Baru</h3>
                </div>
            </div>
            
            <form action="{{ route('admin.jam.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label for="jam_ke" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Sesi Jam Ke- *</label>
                    <input type="number" name="jam_ke" id="jam_ke" placeholder="Misal: 1" min="1" required 
                        class="block w-full px-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] placeholder-slate-400 focus:outline-none focus:border-[#405078] focus:ring-1 focus:ring-[#405078]/20 transition-all">
                </div>

                <div>
                    <label for="kelompok_hari" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kelompok Hari *</label>
                    <select name="kelompok_hari" id="kelompok_hari" required 
                        class="block w-full px-2.5 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] font-medium focus:outline-none focus:border-[#405078] cursor-pointer">
                        <option value="Reguler">Reguler (Senin - Kamis)</option>
                        <option value="Jumat">Jumat</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label for="waktu_mulai" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Waktu Mulai *</label>
                        <input type="time" name="waktu_mulai" id="waktu_mulai" required
                            class="block w-full px-2.5 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078] cursor-pointer">
                    </div>
                    <div>
                        <label for="waktu_selesai" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Waktu Selesai *</label>
                        <input type="time" name="waktu_selesai" id="waktu_selesai" required
                            class="block w-full px-2.5 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078] cursor-pointer">
                    </div>
                </div>

                <div class="pt-1.5">
                    <button type="submit" class="w-full py-2 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center justify-center space-x-1.5 cursor-pointer">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Tambah Jam Pelajaran</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR SESI JAM PELAJARAN -->
        <div id="tableCol" class="lg:col-span-2 space-y-4">
            
            <!-- TABLE REGULER -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
                <div class="px-4 py-3 bg-[#F8FAFC] border-b border-slate-200 flex items-center justify-between">
                    <h4 class="font-bold text-[#1E2538] text-xs flex items-center space-x-1.5">
                        <i data-lucide="calendar-days" class="w-4 h-4 text-[#405078]"></i>
                        <span>Jadwal Reguler (Senin - Kamis)</span>
                    </h4>
                    <span class="px-2 py-0.5 bg-[#405078]/10 text-[#405078] rounded-md text-[11px] font-bold">
                        {{ $jamReguler->count() }} Sesi
                    </span>
                </div>
                <div class="overflow-x-auto max-h-[320px] overflow-y-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="sticky top-0 bg-[#F8FAFC] z-10">
                            <tr class="text-slate-500 font-semibold uppercase tracking-wider border-b border-slate-200">
                                <th class="py-2.5 px-3 w-20 text-center">Jam Ke</th>
                                <th class="py-2.5 px-3 text-center">Waktu Mulai</th>
                                <th class="py-2.5 px-3 text-center">Waktu Selesai</th>
                                <th class="py-2.5 px-3 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-600">
                            @forelse ($jamReguler as $j)
                                <form id="form-update-{{ $j->id_jam }}" action="{{ route('admin.jam.update', $j->id_jam) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                </form>
                                <form id="form-destroy-{{ $j->id_jam }}" action="{{ route('admin.jam.destroy', $j->id_jam) }}" method="POST" onsubmit="return confirm('Hapus jam pelajaran ini?');">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-2 px-3 text-center font-bold text-[#1E2538] text-xs">
                                        {{ $j->jam_ke }}
                                    </td>
                                    <td class="py-2 px-3 text-center">
                                        <input form="form-update-{{ $j->id_jam }}" type="time" name="waktu_mulai" value="{{ substr($j->waktu_mulai, 0, 5) }}" 
                                            class="px-2 py-1 bg-white border border-slate-200 rounded-lg text-xs font-medium text-[#1E2538] text-center focus:outline-none focus:border-[#405078]">
                                    </td>
                                    <td class="py-2 px-3 text-center">
                                        <input form="form-update-{{ $j->id_jam }}" type="time" name="waktu_selesai" value="{{ substr($j->waktu_selesai, 0, 5) }}" 
                                            class="px-2 py-1 bg-white border border-slate-200 rounded-lg text-xs font-medium text-[#1E2538] text-center focus:outline-none focus:border-[#405078]">
                                    </td>
                                    <td class="py-2 px-3 text-center">
                                        <div class="flex items-center justify-center space-x-1">
                                            <button form="form-update-{{ $j->id_jam }}" type="submit" class="px-2 py-1 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-lg text-[11px] font-semibold cursor-pointer">
                                                Simpan
                                            </button>
                                            <button form="form-destroy-{{ $j->id_jam }}" type="submit" class="p-1 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg cursor-pointer">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-400 italic text-xs">Belum ada jam reguler.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TABLE JUMAT -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
                <div class="px-4 py-3 bg-[#F8FAFC] border-b border-slate-200 flex items-center justify-between">
                    <h4 class="font-bold text-[#1E2538] text-xs flex items-center space-x-1.5">
                        <i data-lucide="calendar" class="w-4 h-4 text-emerald-600"></i>
                        <span>Jadwal Khusus Hari Jumat</span>
                    </h4>
                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-md text-[11px] font-bold">
                        {{ $jamJumat->count() }} Sesi
                    </span>
                </div>
                <div class="overflow-x-auto max-h-[320px] overflow-y-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="sticky top-0 bg-[#F8FAFC] z-10">
                            <tr class="text-slate-500 font-semibold uppercase tracking-wider border-b border-slate-200">
                                <th class="py-2.5 px-3 w-20 text-center">Jam Ke</th>
                                <th class="py-2.5 px-3 text-center">Waktu Mulai</th>
                                <th class="py-2.5 px-3 text-center">Waktu Selesai</th>
                                <th class="py-2.5 px-3 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-600">
                            @forelse ($jamJumat as $j)
                                <form id="form-update-{{ $j->id_jam }}" action="{{ route('admin.jam.update', $j->id_jam) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                </form>
                                <form id="form-destroy-{{ $j->id_jam }}" action="{{ route('admin.jam.destroy', $j->id_jam) }}" method="POST" onsubmit="return confirm('Hapus jam pelajaran ini?');">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-2 px-3 text-center font-bold text-[#1E2538] text-xs">
                                        {{ $j->jam_ke }}
                                    </td>
                                    <td class="py-2 px-3 text-center">
                                        <input form="form-update-{{ $j->id_jam }}" type="time" name="waktu_mulai" value="{{ substr($j->waktu_mulai, 0, 5) }}" 
                                            class="px-2 py-1 bg-white border border-slate-200 rounded-lg text-xs font-medium text-[#1E2538] text-center focus:outline-none focus:border-[#405078]">
                                    </td>
                                    <td class="py-2 px-3 text-center">
                                        <input form="form-update-{{ $j->id_jam }}" type="time" name="waktu_selesai" value="{{ substr($j->waktu_selesai, 0, 5) }}" 
                                            class="px-2 py-1 bg-white border border-slate-200 rounded-lg text-xs font-medium text-[#1E2538] text-center focus:outline-none focus:border-[#405078]">
                                    </td>
                                    <td class="py-2 px-3 text-center">
                                        <div class="flex items-center justify-center space-x-1">
                                            <button form="form-update-{{ $j->id_jam }}" type="submit" class="px-2 py-1 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-lg text-[11px] font-semibold cursor-pointer">
                                                Simpan
                                            </button>
                                            <button form="form-destroy-{{ $j->id_jam }}" type="submit" class="p-1 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg cursor-pointer">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-400 italic text-xs">Belum ada jam Jumat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let formOpen = true;
    function toggleFormPanel() {
        const formPanel = document.getElementById('formPanel');
        const tableCol = document.getElementById('tableCol');
        const foldText = document.getElementById('foldText');
        
        formOpen = !formOpen;
        if (!formOpen) {
            formPanel.classList.add('hidden');
            tableCol.classList.remove('lg:col-span-2');
            tableCol.classList.add('lg:col-span-3');
            foldText.innerText = 'Buka Form Tambah';
        } else {
            formPanel.classList.remove('hidden');
            tableCol.classList.add('lg:col-span-2');
            tableCol.classList.remove('lg:col-span-3');
            foldText.innerText = 'Sembunyikan Form Tambah';
        }
    }
</script>
@endsection