@extends('layouts.app')

@section('title', 'Monitoring Kondisi Kelas - Guru Piket')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1E2538] tracking-tight">Monitoring Kondisi Kelas Sekolah</h1>
            <p class="text-xs text-gray-500 mt-1">
                Pantauan langsung aktivitas belajar mengajar di setiap ruang kelas • <span class="font-bold text-[#405078]">{{ $namaHari }}, {{ \Carbon\Carbon::parse($hariIni)->locale('id')->isoFormat('D MMMM Y') }}</span>
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('piket.dashboard') }}" class="px-4 py-2 bg-white border border-[#D1D9EB] hover:bg-gray-50 text-gray-700 rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-xs">
                <i data-lucide="file-plus" class="w-4 h-4 text-[#405078]"></i>
                <span>Kelola Izin Siswa</span>
            </a>
        </div>
    </div>

    <!-- TABEL MONITORING KELAS REAL-TIME -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-[#D1D9EB] flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-ping"></div>
                <h3 class="font-bold text-[#1E2538] text-base">Status Sesi Mengajar Hari Ini ({{ $jadwalHariIni->count() }} Sesi)</h3>
            </div>
            <span class="text-xs text-gray-400 font-semibold">Diperbarui Otomatis</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-[#D1D9EB]">
                        <th class="py-4 px-6 w-28">Jam Ke-</th>
                        <th class="py-4 px-6 w-32">Kelas</th>
                        <th class="py-4 px-6">Mata Pelajaran</th>
                        <th class="py-4 px-6">Guru Pengajar</th>
                        <th class="py-4 px-6 w-28">Ruangan</th>
                        <th class="py-4 px-6 text-center w-36">Kondisi Kelas</th>
                        <th class="py-4 px-6 text-center w-36">Tindakan Piket</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    @forelse($jadwalHariIni as $j)
                        @php 
                            $statusWaktu = $j->statusWaktuMengajar();
                            $isGuruIzin = in_array($j->id_guru, $guruIzinHariIni);
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 font-semibold text-[#1E2538]">Jam {{ $j->jam_mulai }}-{{ $j->jam_selesai }}</td>
                            <td class="py-4 px-6 font-bold text-[#1E2538]">
                                <span class="px-2.5 py-1 bg-gray-100 rounded-md text-xs font-bold text-gray-700">
                                    {{ $j->kelas ? $j->kelas->nama_kelas : '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-medium text-[#1E2538]">{{ $j->mapel ? $j->mapel->nama_mapel : '-' }}</td>
                            <td class="py-4 px-6">
                                <span class="font-semibold text-gray-800">{{ $j->guru ? $j->guru->nama_guru : '-' }}</span>
                                @if($isGuruIzin)
                                    <span class="block text-[10px] text-blue-600 font-bold">📋 Terdata Izin Resmi</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-xs text-gray-500">{{ $j->ruangan ? $j->ruangan->nama_ruangan : '-' }}</td>
                            <td class="py-4 px-6 text-center">
                                @if($j->sudah_diisi)
                                    <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-full space-x-1">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        <span>Berlangsung</span>
                                    </span>
                                @elseif($isGuruIzin)
                                    <span class="inline-flex items-center px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full space-x-1">
                                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                        <span>Guru Izin</span>
                                    </span>
                                @elseif($statusWaktu === 'sekarang')
                                    <span class="inline-flex items-center px-2.5 py-1 bg-rose-50 text-rose-700 text-xs font-semibold rounded-full space-x-1 animate-pulse">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                        <span>Guru Belum Masuk</span>
                                    </span>
                                @elseif($statusWaktu === 'telat')
                                    <span class="inline-flex items-center px-2.5 py-1 bg-rose-100 text-rose-800 text-xs font-bold rounded-full space-x-1">
                                        <span>Kelas Kosong</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 bg-gray-100 text-gray-500 text-xs font-semibold rounded-full space-x-1">
                                        <span>Belum Mulai</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if(!$j->sudah_diisi && ($statusWaktu === 'sekarang' || $statusWaktu === 'telat' || $isGuruIzin))
                                    <button type="button" onclick="bukaModalTugas({{ $j->id_jadwal }}, '{{ $j->id_guru }}', '{{ $j->id_kelas }}', '{{ $j->kelas ? $j->kelas->nama_kelas : '' }}', '{{ $j->guru ? $j->guru->nama_guru : '' }}')"
                                        class="px-3 py-1.5 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-lg text-xs font-semibold transition-colors shadow-xs flex items-center justify-center space-x-1 mx-auto cursor-pointer">
                                        <i data-lucide="clipboard-list" class="w-3.5 h-3.5"></i>
                                        <span>Beri Tugas</span>
                                    </button>
                                @else
                                    <span class="text-gray-300 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400 italic text-xs">
                                Tidak ada jadwal KBM pada hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TABEL TUGAS KELAS KOSONG HARI INI -->
    <div class="bg-white border border-[#D1D9EB] rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-[#D1D9EB] flex items-center justify-between">
            <h3 class="font-bold text-[#1E2538] text-base flex items-center space-x-2">
                <i data-lucide="book-open" class="w-5 h-5 text-[#405078]"></i>
                <span>Tugas Kelas Diberikan Hari Ini ({{ $tugasList->count() }})</span>
            </h3>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($tugasList as $t)
                <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2">
                            <span class="font-bold text-sm text-[#1E2538]">Kelas {{ $t->kelas ? $t->kelas->nama_kelas : '-' }}</span>
                            <span class="text-xs text-gray-500">Guru: {{ $t->guru ? $t->guru->nama_guru : '-' }}</span>
                        </div>
                        <p class="text-xs text-gray-600 font-medium bg-[#F8FAFC] border border-[#D1D9EB] p-2.5 rounded-xl">
                            {{ $t->deskripsi_tugas }}
                        </p>
                    </div>
                    <div>
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full">
                            ✓ Tugas Aktif
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400 italic text-xs">
                    Belum ada penugasan kelas kosong yang dicatat hari ini.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- MODAL FORM PENCATATAN TUGAS KELAS KOSONG -->
<div id="modalTugas" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-2xl border border-[#D1D9EB] shadow-2xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 class="font-bold text-base text-[#1E2538]">Catat Tugas Siswa (Guru Tidak Hadir)</h3>
            <button type="button" onclick="tutupModalTugas()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('piket.tugas-kelas.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="id_jadwal" id="modal_id_jadwal">
            <input type="hidden" name="id_guru" id="modal_id_guru">
            <input type="hidden" name="id_kelas" id="modal_id_kelas">

            <div class="text-xs text-gray-500 space-y-1 bg-[#F8FAFC] p-3 rounded-xl border border-[#D1D9EB]">
                <p>Kelas: <b id="modal_nama_kelas" class="text-[#1E2538]"></b></p>
                <p>Guru Berhalangan: <b id="modal_nama_guru" class="text-[#1E2538]"></b></p>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Instruksi Tugas Siswa *</label>
                <textarea name="deskripsi_tugas" rows="4" required placeholder="Tuliskan tugas yang harus dikerjakan siswa di kelas (misal: Kerjakan LKS Hal 25-30 No 1-10)..."
                    class="block w-full px-3.5 py-2 bg-[#F8FAFC] border border-[#D1D9EB] rounded-xl text-sm text-[#1E2538] placeholder-gray-400 focus:outline-none focus:border-[#405078] focus:ring-2 focus:ring-[#405078]/15"></textarea>
            </div>

            <div class="flex space-x-3 pt-2">
                <button type="submit" class="flex-1 py-2.5 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold transition-colors shadow-sm cursor-pointer">
                    Simpan & Publikasikan Tugas
                </button>
                <button type="button" onclick="tutupModalTugas()" class="px-4 py-2.5 border border-[#D1D9EB] hover:bg-gray-50 text-gray-600 rounded-xl text-xs font-semibold">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function bukaModalTugas(idJadwal, idGuru, idKelas, namaKelas, namaGuru) {
        document.getElementById('modal_id_jadwal').value = idJadwal;
        document.getElementById('modal_id_guru').value = idGuru;
        document.getElementById('modal_id_kelas').value = idKelas;
        document.getElementById('modal_nama_kelas').innerText = namaKelas;
        document.getElementById('modal_nama_guru').innerText = namaGuru;
        document.getElementById('modalTugas').classList.remove('hidden');
    }

    function tutupModalTugas() {
        document.getElementById('modalTugas').classList.add('hidden');
    }
</script>
@endsection
