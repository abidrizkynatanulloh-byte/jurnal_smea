@extends('layouts.app')

@section('title', 'Master Jam Pelajaran - Jurnal Esemkita')

@section('content')
<div class="space-y-4">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Master Jam Pelajaran</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola sesi waktu dan periode jam pelajaran KBM harian</p>
        </div>
        <div>
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="px-3.5 py-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5 text-slate-600"></i>
                <span id="foldText">Sembunyikan Form Tambah</span>
            </button>
        </div>
    </div>

    <!-- Alert / Notifikasi -->
    @if (session('success'))
        <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-xs font-semibold flex items-center space-x-2 shadow-2xs">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg text-xs font-semibold shadow-2xs">
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
        <div id="formPanel" class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-3.5 lg:sticky lg:top-18 z-10">
            <div class="flex items-center space-x-2 pb-2.5 border-b border-slate-100">
                <div class="w-6 h-6 rounded bg-[#1E293B] text-white flex items-center justify-center">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Tambah Sesi Jam Baru</h3>
            </div>
            
            <form action="{{ route('admin.jam.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label for="jam_ke" class="block text-xs font-semibold text-slate-700 mb-1">Sesi Jam Ke- *</label>
                    <input type="number" name="jam_ke" id="jam_ke" placeholder="Misal: 1" min="1" required 
                        class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B] focus:ring-1 focus:ring-[#1E293B] transition-all">
                </div>

                <div>
                    <label for="kelompok_hari" class="block text-xs font-semibold text-slate-700 mb-1">Kelompok Hari *</label>
                    <select name="kelompok_hari" id="kelompok_hari" required 
                        class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-[#1E293B] cursor-pointer">
                        <option value="Reguler">Reguler (Senin - Kamis)</option>
                        <option value="Jumat">Jumat</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label for="waktu_mulai" class="block text-xs font-semibold text-slate-700 mb-1">Waktu Mulai *</label>
                        <input type="time" name="waktu_mulai" id="waktu_mulai" required
                            class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-[#1E293B] cursor-pointer">
                    </div>
                    <div>
                        <label for="waktu_selesai" class="block text-xs font-semibold text-slate-700 mb-1">Waktu Selesai *</label>
                        <input type="time" name="waktu_selesai" id="waktu_selesai" required
                            class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-[#1E293B] cursor-pointer">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 px-4 bg-[#1E293B] hover:bg-[#0F172A] text-white rounded-lg text-xs font-bold transition-all shadow-xs flex items-center justify-center space-x-1.5 cursor-pointer">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Tambah Jam Pelajaran</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR SESI JAM PELAJARAN -->
        <div id="tableCol" class="lg:col-span-2 space-y-4">
            
            <!-- TABLE REGULER -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <h4 class="font-bold text-slate-900 text-xs flex items-center space-x-1.5 uppercase tracking-wider">
                        <i data-lucide="calendar-days" class="w-4 h-4 text-slate-600"></i>
                        <span>Jadwal Reguler (Senin - Kamis)</span>
                    </h4>
                    <span class="text-xs text-slate-500 font-semibold">{{ count($jamReguler) }} Sesi</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[11px] tracking-wider">
                                <th class="py-2.5 px-3.5 text-center w-16">Jam Ke-</th>
                                <th class="py-2.5 px-3.5">Rentang Waktu</th>
                                <th class="py-2.5 px-3.5 text-center w-28">Status</th>
                                <th class="py-2.5 px-3.5 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($jamReguler as $j)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2.5 px-3.5 text-center font-bold text-slate-900">{{ $j->jam_ke }}</td>
                                    <td class="py-2.5 px-3.5 font-medium text-slate-800 font-mono">
                                        {{ substr($j->waktu_mulai, 0, 5) }} - {{ substr($j->waktu_selesai, 0, 5) }} WIB
                                    </td>
                                    <td class="py-2.5 px-3.5 text-center">
                                        @if ($j->is_aktif)
                                            <span class="inline-block px-2 py-0.5 rounded text-[10.5px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 rounded text-[10.5px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3.5 text-center">
                                        <div class="flex items-center justify-center space-x-1.5">
                                            <button type="button" 
                                                onclick="openEditJamModal('{{ $j->id_jam }}', '{{ $j->jam_ke }}', '{{ $j->kelompok_hari }}', '{{ substr($j->waktu_mulai, 0, 5) }}', '{{ substr($j->waktu_selesai, 0, 5) }}', '{{ $j->is_aktif }}')"
                                                class="w-7 h-7 rounded border border-slate-200 hover:border-slate-300 hover:bg-slate-100 text-slate-600 hover:text-slate-900 flex items-center justify-center transition-colors cursor-pointer" title="Edit Jam">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                            </button>
                                            <form action="{{ route('admin.jam.destroy', $j->id_jam) }}" method="POST" onsubmit="return confirm('Hapus jam ke-{{ $j->jam_ke }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-7 h-7 rounded border border-slate-200 hover:border-rose-300 hover:bg-rose-50 text-slate-600 hover:text-rose-600 flex items-center justify-center transition-colors cursor-pointer" title="Hapus Jam">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-400 italic text-xs">
                                        Belum ada jadwal sesi reguler.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TABLE JUMAT -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <h4 class="font-bold text-slate-900 text-xs flex items-center space-x-1.5 uppercase tracking-wider">
                        <i data-lucide="calendar" class="w-4 h-4 text-slate-600"></i>
                        <span>Jadwal Khusus Hari Jumat</span>
                    </h4>
                    <span class="text-xs text-slate-500 font-semibold">{{ count($jamJumat) }} Sesi</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[11px] tracking-wider">
                                <th class="py-2.5 px-3.5 text-center w-16">Jam Ke-</th>
                                <th class="py-2.5 px-3.5">Rentang Waktu</th>
                                <th class="py-2.5 px-3.5 text-center w-28">Status</th>
                                <th class="py-2.5 px-3.5 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($jamJumat as $j)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2.5 px-3.5 text-center font-bold text-slate-900">{{ $j->jam_ke }}</td>
                                    <td class="py-2.5 px-3.5 font-medium text-slate-800 font-mono">
                                        {{ substr($j->waktu_mulai, 0, 5) }} - {{ substr($j->waktu_selesai, 0, 5) }} WIB
                                    </td>
                                    <td class="py-2.5 px-3.5 text-center">
                                        @if ($j->is_aktif)
                                            <span class="inline-block px-2 py-0.5 rounded text-[10.5px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 rounded text-[10.5px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3.5 text-center">
                                        <div class="flex items-center justify-center space-x-1.5">
                                            <button type="button" 
                                                onclick="openEditJamModal('{{ $j->id_jam }}', '{{ $j->jam_ke }}', '{{ $j->kelompok_hari }}', '{{ substr($j->waktu_mulai, 0, 5) }}', '{{ substr($j->waktu_selesai, 0, 5) }}', '{{ $j->is_aktif }}')"
                                                class="w-7 h-7 rounded border border-slate-200 hover:border-slate-300 hover:bg-slate-100 text-slate-600 hover:text-slate-900 flex items-center justify-center transition-colors cursor-pointer" title="Edit Jam">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                            </button>
                                            <form action="{{ route('admin.jam.destroy', $j->id_jam) }}" method="POST" onsubmit="return confirm('Hapus jam ke-{{ $j->jam_ke }} Jumat?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-7 h-7 rounded border border-slate-200 hover:border-rose-300 hover:bg-rose-50 text-slate-600 hover:text-rose-600 flex items-center justify-center transition-colors cursor-pointer" title="Hapus Jam">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-400 italic text-xs">
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

<!-- MODAL EDIT JAM PELAJARAN -->
<div id="modalEditJam" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white border border-slate-200 rounded-xl shadow-xl max-w-sm w-full p-5 space-y-3.5">
        <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
            <h3 class="font-bold text-slate-900 text-sm flex items-center space-x-1.5">
                <i data-lucide="edit-3" class="w-4 h-4 text-slate-700"></i>
                <span>Edit Sesi Jam Pelajaran</span>
            </h3>
            <button type="button" onclick="closeEditJamModal()" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="formEditJam" method="POST" class="space-y-3">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_jam_ke" class="block text-xs font-semibold text-slate-700 mb-1">Jam Ke- *</label>
                <input type="number" name="jam_ke" id="edit_jam_ke" required min="1"
                    class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-[#1E293B]">
            </div>

            <div>
                <label for="edit_kelompok_hari" class="block text-xs font-semibold text-slate-700 mb-1">Kelompok Hari *</label>
                <select name="kelompok_hari" id="edit_kelompok_hari" required 
                    class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-[#1E293B]">
                    <option value="Reguler">Reguler (Senin - Kamis)</option>
                    <option value="Jumat">Jumat</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-2.5">
                <div>
                    <label for="edit_waktu_mulai" class="block text-xs font-semibold text-slate-700 mb-1">Waktu Mulai *</label>
                    <input type="time" name="waktu_mulai" id="edit_waktu_mulai" required
                        class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-[#1E293B]">
                </div>
                <div>
                    <label for="edit_waktu_selesai" class="block text-xs font-semibold text-slate-700 mb-1">Waktu Selesai *</label>
                    <input type="time" name="waktu_selesai" id="edit_waktu_selesai" required
                        class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-[#1E293B]">
                </div>
            </div>

            <div class="flex items-center space-x-2 pt-1">
                <input type="checkbox" name="is_aktif" id="edit_is_aktif" value="1" class="rounded border-slate-300 text-[#1E293B] focus:ring-[#1E293B] cursor-pointer">
                <label for="edit_is_aktif" class="text-xs font-semibold text-slate-700 cursor-pointer">Sesi Aktif Digunakan</label>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2.5 border-t border-slate-100">
                <button type="button" onclick="closeEditJamModal()" class="px-3.5 py-1.5 border border-slate-300 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-4 py-1.5 bg-[#1E293B] hover:bg-[#0F172A] text-white rounded-lg text-xs font-bold transition-all cursor-pointer">
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
            tableCol.className = 'lg:col-span-3 space-y-4';
            foldText.innerText = 'Buka Form Tambah';
            isFolded = true;
        } else {
            formPanel.style.display = 'block';
            tableCol.className = 'lg:col-span-2 space-y-4';
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