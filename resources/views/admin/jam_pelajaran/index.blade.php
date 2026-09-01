@extends('layouts.app')

@section('title', 'Master Jam Pelajaran - Jurnal Esemkita')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Master Jam Pelajaran</h1>
            <p class="text-xs text-gray-500 mt-1">
                Kelola sesi waktu dan periode jam pelajaran mengajar
            </p>
        </div>
    </div>

    <!-- Responsive Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- BAGIAN 1: FORM TAMBAH JAM BARU -->
        <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm p-6 lg:sticky lg:top-8">
            <h3 class="font-bold text-[#1E2538] text-base mb-4 flex items-center space-x-2">
                <i data-lucide="plus-circle" class="w-5 h-5 text-[#405078]"></i>
                <span>Tambah Sesi Jam Baru</span>
            </h3>
            
            <form action="{{ route('admin.jam.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="jam_ke" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Sesi Jam Ke-</label>
                    <input type="number" name="jam_ke" id="jam_ke" placeholder="Misal: 1" min="1" required 
                        class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="waktu_mulai" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Waktu Mulai</label>
                        <input type="time" name="waktu_mulai" id="waktu_mulai" required
                            class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                    </div>
                    <div>
                        <label for="waktu_selesai" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Waktu Selesai</label>
                        <input type="time" name="waktu_selesai" id="waktu_selesai" required
                            class="block w-full px-3.5 py-2.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                    </div>
                </div>

                <button type="submit" class="w-full mt-2 py-3 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center space-x-2 cursor-pointer">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Tambah Jam Pelajaran</span>
                </button>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR SESI JAM PELAJARAN -->
        <div class="lg:col-span-2">
            
            <!-- TABLE CARD -->
            <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#F8FAFC] text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                                <th class="py-4 px-6 text-center w-24">Jam Ke-</th>
                                <th class="py-4 px-6 text-center">Waktu Mulai</th>
                                <th class="py-4 px-6 text-center">Waktu Selesai</th>
                                <th class="py-4 px-6 text-center w-64">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse ($jamList as $j)
                                <!-- Row forms with HTML5 form attribute -->
                                <form id="form-update-{{ $j->id_jam }}" action="{{ route('admin.jam.update', $j->id_jam) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                </form>
                                <form id="form-destroy-{{ $j->id_jam }}" action="{{ route('admin.jam.destroy', $j->id_jam) }}" method="POST" onsubmit="return confirm('Hapus jam pelajaran ini?');">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-6 text-center font-bold text-[#1E2538] text-base">
                                        {{ $j->jam_ke }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <input type="time" name="waktu_mulai" value="{{ substr($j->waktu_mulai, 0, 5) }}" form="form-update-{{ $j->id_jam }}" required
                                            class="inline-block px-3 py-1.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-lg text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <input type="time" name="waktu_selesai" value="{{ substr($j->waktu_selesai, 0, 5) }}" form="form-update-{{ $j->id_jam }}" required
                                            class="inline-block px-3 py-1.5 bg-[#F8FAFC] border border-[#D1D9EB] rounded-lg text-sm text-[#1E2538] focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15 transition-all cursor-pointer">
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex items-center justify-center space-x-2.5">
                                            <!-- Update Button -->
                                            <button type="submit" form="form-update-{{ $j->id_jam }}" class="px-3.5 py-1.5 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-lg text-xs font-bold transition-colors shadow-sm flex items-center space-x-1 cursor-pointer">
                                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                <span>Update</span>
                                            </button>
                                            
                                            <!-- Delete Button -->
                                            <button type="submit" form="form-destroy-{{ $j->id_jam }}" class="px-3.5 py-1.5 border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 rounded-lg text-xs font-bold transition-colors flex items-center space-x-1 cursor-pointer">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-400 italic">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                        Belum ada sesi jam pelajaran. Silakan tambah di samping.
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
@endsection