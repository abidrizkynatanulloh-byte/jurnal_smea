@extends('layouts.app')

@section('title', 'Data Guru & Pegawai - Jurnal Esemkita')

@section('content')
<div class="flex-1 flex flex-col min-h-0 space-y-2.5">
    <!-- Header Halaman (Proporsi Gambar 1 - FIXED) -->
    <div class="shrink-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div class="flex items-center space-x-2.5">
            <h1 class="text-lg font-bold text-slate-900 tracking-tight">Data Guru & Pegawai</h1>
            <span class="px-2 py-0.5 text-[11px] font-semibold bg-slate-100 text-slate-700 rounded-md font-mono tabular-nums border border-slate-200">
                {{ $totalGuru }} Pendidik
            </span>
        </div>
        <div class="flex items-center space-x-1.5">
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="h-8 px-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer">
                <i data-lucide="panel-left-close" class="w-3.5 h-3.5 text-slate-500"></i>
                <span id="btnToggleFormText">Sembunyikan Form Tambah</span>
            </button>
            <a href="{{ route('admin.guru.trash') }}" class="h-8 px-3 border border-rose-200 bg-rose-50/80 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                <span>Sampah</span>
            </a>
        </div>
    </div>

    <!-- Layout Grid Utama (Full Viewport Height - Proporsi Gambar 1) -->
    <div class="flex-1 min-h-0 grid grid-cols-1 lg:grid-cols-3 gap-3.5 items-stretch" id="masterDataGrid">
        
        <!-- BAGIAN 1: FORM TAMBAH GURU (UKURAN KOMPAK & RAMPING SESUAI GAMBAR 1) -->
        <div id="formCol" class="bg-white border border-slate-200/90 rounded-xl p-3.5 shadow-2xs space-y-2.5 overflow-y-auto h-full max-h-full">
            <div class="flex items-center space-x-2 pb-2 border-b border-slate-100 shrink-0">
                <div class="w-5 h-5 rounded-md bg-[#1E2538] text-white flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-3 h-3"></i>
                </div>
                <span class="font-bold text-xs text-slate-900 tracking-tight uppercase">TAMBAH PEGAWAI BARU</span>
            </div>

            <form action="{{ route('admin.guru.store') }}" method="POST" class="space-y-2">
                @csrf

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">NIP / Kode Pegawai *</label>
                    <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Contoh: 198001012005011001" required
                        class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 font-mono transition-colors">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Nama Lengkap & Gelar *</label>
                    <input type="text" name="nama_guru" value="{{ old('nama_guru') }}" placeholder="Contoh: Drs. Budi Santoso, M.Pd" required
                        class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Nomor HP (WhatsApp)</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890"
                        class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 font-mono transition-colors">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Role / Jabatan Sistem *</label>
                    <select name="role" required
                        class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                        <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru Mata Pelajaran</option>
                        <option value="guru_piket" {{ old('role') == 'guru_piket' ? 'selected' : '' }}>Guru Piket</option>
                        <option value="kepala_sekolah" {{ old('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                        <option value="wakasis_siswa" {{ old('role') == 'wakasis_siswa' ? 'selected' : '' }}>Wakil Kesiswaan (Siswa)</option>
                        <option value="wakasis_guru" {{ old('role') == 'wakasis_guru' ? 'selected' : '' }}>Wakil Kesiswaan (Guru / SDM)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Password Akun Login *</label>
                    <input type="password" name="password" placeholder="Minimal 4 karakter" required
                        class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                </div>

                <div class="pt-1">
                    <button type="submit" class="w-full h-8.5 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center space-x-1.5 shadow-xs cursor-pointer">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Simpan Pegawai</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: DATA TABLE CARD (PROPORSI & DENSITAS SESUAI GAMBAR 1) -->
        <div id="tableCol" class="lg:col-span-2 flex flex-col h-full min-h-0">
            <div class="bg-white border border-slate-200/90 rounded-xl shadow-2xs overflow-hidden flex flex-col h-full min-h-0">
                <!-- Top Control Bar (Search + Jabatan) -->
                <div class="shrink-0 px-3.5 py-2 border-b border-slate-200 bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <form action="{{ route('admin.guru.index') }}" method="GET" class="flex flex-1 flex-col sm:flex-row items-center gap-2 w-full">
                        <div class="relative flex-1 w-full">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="search" id="liveSearchGuru" value="{{ request('search') }}" placeholder="Cari Nama, NIP, atau Jabatan..." 
                                class="w-full h-8 pl-8 pr-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-800 transition-colors">
                        </div>

                        <div class="w-full sm:w-40">
                            <select name="jabatan" onchange="this.form.submit()" class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                                <option value="">Semua Jabatan</option>
                                <option value="Guru" {{ request('jabatan') == 'Guru' ? 'selected' : '' }}>Guru Mapel</option>
                                <option value="Guru Piket" {{ request('jabatan') == 'Guru Piket' ? 'selected' : '' }}>Guru Piket</option>
                                <option value="Kepala Sekolah" {{ request('jabatan') == 'Kepala Sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                <option value="Wakasis Siswa" {{ request('jabatan') == 'Wakasis Siswa' ? 'selected' : '' }}>Wakasis Siswa</option>
                                <option value="Wakasis Guru" {{ request('jabatan') == 'Wakasis Guru' ? 'selected' : '' }}>Wakasis Guru</option>
                            </select>
                        </div>

                        @if (request('search') || request('jabatan'))
                            <a href="{{ route('admin.guru.index') }}" class="h-8 px-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-semibold flex items-center transition-colors shrink-0">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Table Body (INTERNAL SCROLL DENGAN DENSITAS KOMPAK SESUAI GAMBAR 1) -->
                <div class="flex-1 overflow-y-auto overflow-x-auto min-h-0">
                    <table class="w-full text-left border-collapse text-xs" id="tableGuru">
                        <thead class="sticky top-0 bg-white border-b border-slate-200 z-10 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="py-2.5 px-3.5 text-center w-12 bg-white">NO</th>
                                <th class="py-2.5 px-3.5 bg-white">NAMA LENGKAP & NIP</th>
                                <th class="py-2.5 px-3.5 w-40 bg-white">JABATAN / ROLE</th>
                                <th class="py-2.5 px-3.5 w-36 bg-white">NO. HP (WHATSAPP)</th>
                                <th class="py-2.5 px-3.5 text-center w-20 bg-white">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700" id="guruTbody">
                            @forelse ($guruList as $idx => $g)
                                <tr class="hover:bg-slate-50/80 transition-colors guru-row" data-search="{{ strtolower($g->nama_guru . ' ' . $g->nip . ' ' . $g->jabatan) }}">
                                    <td class="py-2 px-3.5 text-center font-medium text-slate-400 text-xs tabular-nums">
                                        {{ $guruList->firstItem() + $idx }}
                                    </td>
                                    <td class="py-2 px-3.5">
                                        <p class="font-semibold text-slate-900 text-xs leading-tight">{{ $g->nama_guru }}</p>
                                        <p class="text-[11px] text-slate-400 font-mono tabular-nums mt-0.5">NIP: {{ $g->nip }}</p>
                                    </td>
                                    <td class="py-2 px-3.5">
                                        <span class="inline-block px-2 py-0.5 rounded text-[11px] font-medium border
                                            @if($g->jabatan === 'Kepala Sekolah') bg-emerald-50 text-emerald-800 border-emerald-200
                                            @elseif(str_contains($g->jabatan, 'Wakasis')) bg-indigo-50 text-indigo-800 border-indigo-200
                                            @elseif($g->jabatan === 'Guru Piket') bg-amber-50 text-amber-800 border-amber-200
                                            @else bg-slate-100 text-slate-700 border-slate-200 @endif">
                                            {{ $g->jabatan ?? 'Guru' }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-3.5 text-xs text-slate-600 font-mono tabular-nums">
                                        {{ $g->no_hp ?: '-' }}
                                    </td>
                                    <td class="py-2 px-3.5 text-center">
                                        <div class="flex items-center justify-center space-x-1">
                                            <button type="button" 
                                                onclick="openEditGuruModal('{{ $g->id_guru }}', '{{ addslashes($g->nama_guru) }}', '{{ $g->nip }}', '{{ $g->no_hp ?? '' }}', '{{ $g->user ? $g->user->role : 'guru' }}')"
                                                class="w-6.5 h-6.5 rounded border border-slate-200 hover:border-slate-300 hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Edit Guru">
                                                <i data-lucide="edit-2" class="w-3 h-3"></i>
                                            </button>
                                            <form action="{{ route('admin.guru.destroy', $g->id_guru) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data {{ addslashes($g->nama_guru) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-6.5 h-6.5 rounded border border-slate-200 hover:border-rose-200 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors shadow-2xs cursor-pointer" title="Hapus Guru">
                                                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-400 italic text-xs">
                                        <i data-lucide="inbox" class="w-7 h-7 mx-auto mb-1.5 text-slate-300"></i>
                                        Tidak ada data guru yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Bottom Pagination Bar (Sesuai Gambar 1 & Gambar 2) -->
                <div class="shrink-0">
                    <x-pagination-bar :paginator="$guruList" />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT DATA GURU -->
<div id="modalEditGuru" class="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white border border-slate-200 rounded-xl shadow-xl max-w-md w-full p-4.5 space-y-3">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <span class="font-bold text-slate-900 text-xs uppercase tracking-tight flex items-center space-x-1.5">
                <i data-lucide="edit-2" class="w-3.5 h-3.5 text-slate-700"></i>
                <span>Edit Data Pegawai</span>
            </span>
            <button type="button" onclick="closeEditGuruModal()" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="formEditGuru" method="POST" class="space-y-2.5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">NIP / Kode Pegawai *</label>
                <input type="text" name="nip" id="edit_nip" required
                    class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-mono focus:outline-none focus:border-slate-800">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Nama Lengkap & Gelar *</label>
                <input type="text" name="nama_guru" id="edit_nama_guru" required
                    class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-slate-800">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Nomor HP (WhatsApp)</label>
                <input type="text" name="no_hp" id="edit_no_hp" placeholder="Contoh: 081234567890"
                    class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-mono focus:outline-none focus:border-slate-800">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Role / Jabatan Sistem *</label>
                <select name="role" id="edit_role" required
                    class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-slate-800 cursor-pointer">
                    <option value="guru">Guru Mata Pelajaran</option>
                    <option value="guru_piket">Guru Piket</option>
                    <option value="kepala_sekolah">Kepala Sekolah</option>
                    <option value="wakasis_siswa">Wakil Kesiswaan (Siswa)</option>
                    <option value="wakasis_guru">Wakil Kesiswaan (Guru / SDM)</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-0.5">Ganti Password (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" placeholder="Masukkan password baru..."
                    class="w-full h-8 px-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-slate-800">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeEditGuruModal()" class="h-8 px-3 border border-slate-200 rounded-lg text-xs font-medium text-slate-600 hover:bg-slate-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-8 px-3.5 bg-[#1E2538] hover:bg-[#161c2c] text-white rounded-lg text-xs font-semibold transition-colors cursor-pointer shadow-2xs">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let isFormFolded = false;
    function toggleFormPanel() {
        const formCol = document.getElementById('formCol');
        const tableCol = document.getElementById('tableCol');
        const btnText = document.getElementById('btnToggleFormText');

        if (!isFormFolded) {
            formCol.style.display = 'none';
            tableCol.className = 'lg:col-span-3 flex flex-col h-full min-h-0';
            btnText.innerText = 'Buka Form Tambah';
            isFormFolded = true;
        } else {
            formCol.style.display = 'block';
            tableCol.className = 'lg:col-span-2 flex flex-col h-full min-h-0';
            btnText.innerText = 'Sembunyikan Form Tambah';
            isFormFolded = false;
        }
    }

    function openEditGuruModal(id, nama, nip, noHp, role) {
        document.getElementById('edit_nama_guru').value = nama;
        document.getElementById('edit_nip').value = nip;
        document.getElementById('edit_no_hp').value = noHp;
        document.getElementById('edit_role').value = role;

        document.getElementById('formEditGuru').action = `/admin/guru/${id}`;
        document.getElementById('modalEditGuru').classList.remove('hidden');
    }

    function closeEditGuruModal() {
        document.getElementById('modalEditGuru').classList.add('hidden');
    }

    // Live Instant Search
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('liveSearchGuru');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('.guru-row');
                rows.forEach(row => {
                    const text = row.getAttribute('data-search') || '';
                    if (text.includes(q)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection
