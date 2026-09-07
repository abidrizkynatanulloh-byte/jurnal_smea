@extends('layouts.app')

@section('title', 'Monitoring Kondisi Kelas - Guru Piket')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Monitoring Kondisi Kelas Sekolah</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Pantauan langsung aktivitas belajar mengajar di setiap ruang kelas • <span class="font-bold text-slate-700">{{ $namaHari }}, {{ \Carbon\Carbon::parse($hariIni)->locale('id')->isoFormat('D MMMM Y') }}</span>
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('piket.dashboard') }}" class="h-8.5 px-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs">
                <i data-lucide="file-plus" class="w-3.5 h-3.5 text-slate-500"></i>
                <span>Kelola Izin Siswa</span>
            </a>
        </div>
    </div>

    <!-- TABEL MONITORING KELAS REAL-TIME -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></div>
                <h3 class="font-bold text-slate-900 text-xs">Status Sesi Mengajar Hari Ini ({{ $jadwalHariIni->count() }} Sesi)</h3>
            </div>
            <span class="text-[11px] text-slate-400 font-semibold">Diperbarui Otomatis</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-white border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-2 px-3.5 w-24">Jam Ke-</th>
                        <th class="py-2 px-3.5 w-28">Kelas</th>
                        <th class="py-2 px-3.5">Mata Pelajaran</th>
                        <th class="py-2 px-3.5">Guru Pengajar</th>
                        <th class="py-2 px-3.5 w-24">Ruangan</th>
                        <th class="py-2 px-3.5 text-center w-32">Kondisi Kelas</th>
                        <th class="py-2 px-3.5 text-center w-28">Tindakan Piket</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($jadwalHariIni as $j)
                        @php 
                            $statusWaktu = $j->statusWaktuMengajar();
                            $isGuruIzin = in_array($j->id_guru, $guruIzinHariIni);
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2 px-3.5 font-semibold text-slate-800 text-xs">Jam {{ $j->jam_mulai }}-{{ $j->jam_selesai }}</td>
                            <td class="py-2 px-3.5 font-bold text-slate-800 text-xs">
                                <span class="px-2 py-0.5 bg-slate-100 rounded-md text-xs font-bold text-slate-700 border border-slate-200/60">
                                    {{ $j->kelas ? $j->kelas->nama_kelas : '-' }}
                                </span>
                            </td>
                            <td class="py-2 px-3.5 font-medium text-slate-800 text-xs">{{ $j->mapel ? $j->mapel->nama_mapel : '-' }}</td>
                            <td class="py-2 px-3.5">
                                <span class="font-semibold text-slate-800 text-xs">{{ $j->guru ? $j->guru->nama_guru : '-' }}</span>
                                @if($isGuruIzin)
                                    <span class="block text-[10px] text-blue-600 font-bold">📋 Terdata Izin</span>
                                @endif
                            </td>
                            <td class="py-2 px-3.5 text-xs text-slate-500">{{ $j->ruangan ? $j->ruangan->nama_ruangan : '-' }}</td>
                            <td class="py-2 px-3.5 text-center">
                                @if($j->sudah_diisi)
                                    <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-semibold rounded-md border border-emerald-200/60 space-x-1">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        <span>Berlangsung</span>
                                    </span>
                                @elseif($isGuruIzin)
                                    <span class="inline-flex items-center px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-semibold rounded-md border border-blue-200/60 space-x-1">
                                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                        <span>Guru Izin</span>
                                    </span>
                                @elseif($statusWaktu === 'sekarang')
                                    <span class="inline-flex items-center px-2 py-0.5 bg-rose-50 text-rose-700 text-[10px] font-semibold rounded-md border border-rose-200/60 space-x-1 animate-pulse">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                        <span>Belum Masuk</span>
                                    </span>
                                @elseif($statusWaktu === 'telat')
                                    <span class="inline-flex items-center px-2 py-0.5 bg-rose-100 text-rose-800 text-[10px] font-bold rounded-md border border-rose-200">
                                        <span>Kelas Kosong</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 bg-slate-100 text-slate-500 text-[10px] font-semibold rounded-md border border-slate-200">
                                        <span>Belum Mulai</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-2 px-3.5 text-center">
                                @if(!$j->sudah_diisi && ($statusWaktu === 'sekarang' || $statusWaktu === 'telat' || $isGuruIzin))
                                    <button type="button" onclick="bukaModalTugas({{ $j->id_jadwal }}, '{{ $j->id_guru }}', '{{ $j->id_kelas }}', '{{ $j->kelas ? $j->kelas->nama_kelas : '' }}', '{{ $j->guru ? $j->guru->nama_guru : '' }}')"
                                        class="h-6.5 px-2 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-md text-xs font-semibold transition-colors shadow-2xs flex items-center justify-center space-x-1 mx-auto cursor-pointer">
                                        <i data-lucide="clipboard-list" class="w-3 h-3"></i>
                                        <span>Beri Tugas</span>
                                    </button>
                                @else
                                    <span class="text-slate-300 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400 italic text-xs">
                                Tidak ada jadwal KBM pada hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TABEL TUGAS KELAS KOSONG HARI INI -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-xs flex items-center space-x-1.5">
                <i data-lucide="book-open" class="w-4 h-4 text-slate-500"></i>
                <span>Tugas Kelas Diberikan Hari Ini ({{ $tugasList->count() }})</span>
            </h3>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($tugasList as $t)
                <div class="p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2">
                            <span class="font-bold text-xs text-slate-900">Kelas {{ $t->kelas ? $t->kelas->nama_kelas : '-' }}</span>
                            <span class="text-xs text-slate-500">Guru: {{ $t->guru ? $t->guru->nama_guru : '-' }}</span>
                        </div>
                        <p class="text-xs text-slate-600 font-medium bg-slate-50 border border-slate-200 p-2 rounded-lg">
                            {{ $t->deskripsi_tugas }}
                        </p>
                    </div>
                    <div>
                        <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-[11px] font-bold rounded-md border border-emerald-200/60">
                            ✓ Tugas Aktif
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-slate-400 italic text-xs">
                    Belum ada penugasan kelas kosong yang dicatat hari ini.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- MODAL FORM PENCATATAN TUGAS KELAS KOSONG -->
<div id="modalTugas" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-xl border border-slate-200 shadow-2xl max-w-md w-full p-4 space-y-3">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <h3 class="font-bold text-sm text-slate-900">Catat Tugas Siswa (Guru Tidak Hadir)</h3>
            <button type="button" onclick="tutupModalTugas()" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('piket.tugas-kelas.store') }}" method="POST" class="space-y-3">
            @csrf
            <input type="hidden" name="id_jadwal" id="modal_id_jadwal">
            <input type="hidden" name="id_guru" id="modal_id_guru">
            <input type="hidden" name="id_kelas" id="modal_id_kelas">

            <div class="text-xs text-slate-500 space-y-0.5 bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                <p>Kelas: <b id="modal_nama_kelas" class="text-slate-800"></b></p>
                <p>Guru Berhalangan: <b id="modal_nama_guru" class="text-slate-800"></b></p>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Instruksi Tugas Siswa *</label>
                <textarea name="deskripsi_tugas" rows="3" required placeholder="Tuliskan tugas yang harus dikerjakan siswa di kelas (misal: Kerjakan LKS Hal 25-30 No 1-10)..."
                    class="block w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#1E2538]"></textarea>
            </div>

            <div class="flex space-x-2 pt-1">
                <button type="submit" class="flex-1 h-8.5 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg text-xs font-semibold transition-colors shadow-2xs cursor-pointer">
                    Simpan & Publikasikan Tugas
                </button>
                <button type="button" onclick="tutupModalTugas()" class="h-8.5 px-3 border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-lg text-xs font-semibold">
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
