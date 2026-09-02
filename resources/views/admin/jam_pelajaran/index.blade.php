@extends('layouts.app')

@section('title', 'Master Jam Pelajaran - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Master Jam Pelajaran</h1>
            <p class="text-xs text-gray-500 mt-1">
                Kelola sesi waktu dan periode jam pelajaran mengajar
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="px-4 py-2 border border-[#D1D9EB] bg-white hover:bg-gray-50 text-gray-700 rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-xs cursor-pointer">
                <i data-lucide="panel-left" class="w-4 h-4 text-[#405078]"></i>
                <span id="foldText">Sembunyikan Form Tambah</span>
            </button>
        </div>
    </div>

    <!-- Responsive Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- BAGIAN 1: FORM TAMBAH JAM BARU (DAPAT DI-FOLD) -->
        <div id="formPanel" class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-6 lg:sticky lg:top-6 transition-all duration-300">
            <h3 class="font-bold text-[#1E2538] text-base mb-4 flex items-center space-x-2">
                <i data-lucide="plus-circle" class="w-5 h-5 text-[#405078]"></i>
                <span>Tambah Sesi Jam Baru</span>
            </h3>
            
            <form action="{{ route('admin.jam.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="jam_ke" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Sesi Jam Ke- *</label>
                    <input type="number" name="jam_ke" id="jam_ke" placeholder="Misal: 1" min="1" required 
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <div>
                    <label for="kelompok_hari" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Kelompok Hari *</label>
                    <select name="kelompok_hari" id="kelompok_hari" required class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                        <option value="Reguler">Reguler (Senin - Kamis)</option>
                        <option value="Jumat">Jumat</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="waktu_mulai" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Waktu Mulai *</label>
                        <input type="time" name="waktu_mulai" id="waktu_mulai" required
                            class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] cursor-pointer">
                    </div>
                    <div>
                        <label for="waktu_selesai" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Waktu Selesai *</label>
                        <input type="time" name="waktu_selesai" id="waktu_selesai" required
                            class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] cursor-pointer">
                    </div>
                </div>

                <button type="submit" class="w-full mt-2 py-3 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center space-x-2 cursor-pointer">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Tambah Jam Pelajaran</span>
                </button>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR SESI JAM PELAJARAN -->
        <div id="tableCol" class="lg:col-span-2 space-y-6">
            
            <!-- TABLE REGULER -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-[#F8FAFC] border-b border-[#D1D9EB]">
                    <h4 class="font-bold text-[#1E2538] text-sm flex items-center space-x-2">
                        <i data-lucide="calendar-days" class="w-4 h-4 text-[#405078]"></i>
                        <span>Jadwal Reguler (Senin - Kamis)</span>
                    </h4>
                </div>
                <div class="overflow-x-auto max-h-[380px] overflow-y-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="sticky top-0 bg-[#F8FAFC] z-10">
                            <tr class="text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                                <th class="py-3 px-4 text-center w-24">Jam Ke-</th>
                                <th class="py-3 px-4 text-center">Waktu Mulai</th>
                                <th class="py-3 px-4 text-center">Waktu Selesai</th>
                                <th class="py-3 px-4 text-center w-48">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse ($jamReguler as $j)
                                <form id="form-update-{{ $j->id_jam }}" action="{{ route('admin.jam.update', $j->id_jam) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                </form>
                                <form id="form-destroy-{{ $j->id_jam }}" action="{{ route('admin.jam.destroy', $j->id_jam) }}" method="POST" onsubmit="return confirm('Hapus jam pelajaran ini?');">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-3 px-4 text-center font-bold text-[#1E2538] text-sm">
                                        {{ $j->jam_ke }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <input form="form-update-{{ $j->id_jam }}" type="time" name="waktu_mulai" value="{{ substr($j->waktu_mulai, 0, 5) }}" 
                                            class="px-2 py-1 bg-white border border-[#D1D9EB] rounded-lg text-xs font-medium text-[#1E2538] text-center focus:outline-none focus:border-[#405078]">
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <input form="form-update-{{ $j->id_jam }}" type="time" name="waktu_selesai" value="{{ substr($j->waktu_selesai, 0, 5) }}" 
                                            class="px-2 py-1 bg-white border border-[#D1D9EB] rounded-lg text-xs font-medium text-[#1E2538] text-center focus:outline-none focus:border-[#405078]">
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center space-x-1.5">
                                            <button form="form-update-{{ $j->id_jam }}" type="submit" class="px-2.5 py-1 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-lg text-xs font-semibold cursor-pointer">
                                                Simpan
                                            </button>
                                            <button form="form-destroy-{{ $j->id_jam }}" type="submit" class="p-1 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg cursor-pointer">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-gray-400 italic text-xs">Belum ada jam reguler.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TABLE JUMAT -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-[#F8FAFC] border-b border-[#D1D9EB]">
                    <h4 class="font-bold text-[#1E2538] text-sm flex items-center space-x-2">
                        <i data-lucide="calendar" class="w-4 h-4 text-emerald-600"></i>
                        <span>Jadwal Khusus Hari Jumat</span>
                    </h4>
                </div>
                <div class="overflow-x-auto max-h-[380px] overflow-y-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="sticky top-0 bg-[#F8FAFC] z-10">
                            <tr class="text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                                <th class="py-3 px-4 text-center w-24">Jam Ke-</th>
                                <th class="py-3 px-4 text-center">Waktu Mulai</th>
                                <th class="py-3 px-4 text-center">Waktu Selesai</th>
                                <th class="py-3 px-4 text-center w-48">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse ($jamJumat as $j)
                                <form id="form-update-{{ $j->id_jam }}" action="{{ route('admin.jam.update', $j->id_jam) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                </form>
                                <form id="form-destroy-{{ $j->id_jam }}" action="{{ route('admin.jam.destroy', $j->id_jam) }}" method="POST" onsubmit="return confirm('Hapus jam pelajaran ini?');">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-3 px-4 text-center font-bold text-[#1E2538] text-sm">
                                        {{ $j->jam_ke }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <input form="form-update-{{ $j->id_jam }}" type="time" name="waktu_mulai" value="{{ substr($j->waktu_mulai, 0, 5) }}" 
                                            class="px-2 py-1 bg-white border border-[#D1D9EB] rounded-lg text-xs font-medium text-[#1E2538] text-center focus:outline-none focus:border-[#405078]">
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <input form="form-update-{{ $j->id_jam }}" type="time" name="waktu_selesai" value="{{ substr($j->waktu_selesai, 0, 5) }}" 
                                            class="px-2 py-1 bg-white border border-[#D1D9EB] rounded-lg text-xs font-medium text-[#1E2538] text-center focus:outline-none focus:border-[#405078]">
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center space-x-1.5">
                                            <button form="form-update-{{ $j->id_jam }}" type="submit" class="px-2.5 py-1 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-lg text-xs font-semibold cursor-pointer">
                                                Simpan
                                            </button>
                                            <button form="form-destroy-{{ $j->id_jam }}" type="submit" class="p-1 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg cursor-pointer">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-gray-400 italic text-xs">Belum ada jam khusus Jumat.</td>
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
            foldText.innerText = 'Tampilkan Form Tambah';
        } else {
            formPanel.classList.remove('hidden');
            tableCol.classList.add('lg:col-span-2');
            tableCol.classList.remove('lg:col-span-3');
            foldText.innerText = 'Sembunyikan Form Tambah';
        }
    }
</script>
@endsection