@extends('layouts.app')

@section('title', 'Master Data Guru & Pegawai - Jurnal Esemkita')

@section('content')
<div class="space-y-5">
    <!-- Header Halaman -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
        <div>
            <h1 class="text-xl font-extrabold text-[#1E2538] tracking-tight">Data Guru & Pegawai</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola data tenaga pendidik, penugasan jabatan, dan akun akses sistem</p>
        </div>
        <div class="flex items-center space-x-2.5">
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="px-3 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-[#1E2538] rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5 text-[#405078]"></i>
                <span id="btnToggleFormText">Sembunyikan Form Tambah</span>
            </button>
            <a href="{{ route('admin.guru.trash') }}" class="px-3 py-1.5 border border-rose-200 bg-rose-50/60 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                <span>Tong Sampah</span>
            </a>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start" id="masterDataGrid">
        <!-- BAGIAN 1: FORM TAMBAH GURU / PEGAWAI BARU (STICKY & FOLDABLE) -->
        <div id="formCol" class="lg:col-span-1 bg-white border border-slate-200 rounded-2xl p-4.5 shadow-xs space-y-3.5 lg:sticky lg:top-20 z-10 transition-all duration-300">
            <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <i data-lucide="user-plus" class="w-4 h-4 text-[#405078]"></i>
                    <h3 class="font-bold text-[#1E2538] text-xs uppercase tracking-wider">Tambah Pegawai Baru</h3>
                </div>
            </div>

            <form action="{{ route('admin.guru.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">NIP / Kode Pegawai *</label>
                    <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Contoh: 198001012005011001" required
                        class="block w-full px-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] placeholder-slate-400 focus:outline-none focus:border-[#405078] focus:ring-1 focus:ring-[#405078]/20 transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap & Gelar *</label>
                    <input type="text" name="nama_guru" value="{{ old('nama_guru') }}" placeholder="Contoh: Drs. Budi Santoso, M.Pd" required
                        class="block w-full px-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] placeholder-slate-400 focus:outline-none focus:border-[#405078] focus:ring-1 focus:ring-[#405078]/20 transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor HP (WhatsApp)</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890"
                        class="block w-full px-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] placeholder-slate-400 focus:outline-none focus:border-[#405078] focus:ring-1 focus:ring-[#405078]/20 transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Role / Jabatan Sistem *</label>
                    <select name="role" required
                        class="block w-full px-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] font-medium focus:outline-none focus:border-[#405078] cursor-pointer">
                        <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru Mata Pelajaran</option>
                        <option value="guru_piket" {{ old('role') == 'guru_piket' ? 'selected' : '' }}>Guru Piket</option>
                        <option value="kepala_sekolah" {{ old('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                        <option value="wakasis_siswa" {{ old('role') == 'wakasis_siswa' ? 'selected' : '' }}>Wakil Kesiswaan (Siswa)</option>
                        <option value="wakasis_guru" {{ old('role') == 'wakasis_guru' ? 'selected' : '' }}>Wakil Kesiswaan (Guru / SDM)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Password Akun Login *</label>
                    <input type="password" name="password" placeholder="Minimal 4 karakter" required
                        class="block w-full px-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] placeholder-slate-400 focus:outline-none focus:border-[#405078] focus:ring-1 focus:ring-[#405078]/20 transition-all">
                </div>

                <div class="pt-1.5">
                    <button type="submit" class="w-full py-2 px-4 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center justify-center space-x-1.5 cursor-pointer">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        <span>Simpan Pegawai</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR & LIVE FILTER GURU (MELEBAR SAAT FORM DI-FOLD) -->
        <div id="tableCol" class="lg:col-span-2 space-y-3.5">
            
            <!-- STICKY FILTER CARD (DENGAN LIVE INSTANT SEARCH) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-3.5 shadow-xs sticky top-20 z-20">
                <form action="{{ route('admin.guru.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pencarian Cepat (Ketik Langsung Tersaring)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="search" class="w-3.5 h-3.5 text-[#8697C3]"></i>
                            </div>
                            <input type="text" name="search" id="liveSearchGuru" value="{{ request('search') }}" placeholder="Ketik Nama, NIP, atau Jabatan..." 
                                class="block w-full pl-8.5 pr-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] placeholder-slate-400 focus:outline-none focus:border-[#405078] focus:ring-1 focus:ring-[#405078]/20 transition-all">
                        </div>
                    </div>

                    <div class="w-full sm:w-44">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Filter Jabatan</label>
                        <select name="jabatan" onchange="this.form.submit()" class="block w-full px-2.5 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] font-medium focus:outline-none focus:border-[#405078] cursor-pointer">
                            <option value="">Semua Jabatan ({{ $totalGuru }})</option>
                            <option value="Guru" {{ request('jabatan') == 'Guru' ? 'selected' : '' }}>Guru Mata Pelajaran</option>
                            <option value="Guru Piket" {{ request('jabatan') == 'Guru Piket' ? 'selected' : '' }}>Guru Piket</option>
                            <option value="Kepala Sekolah" {{ request('jabatan') == 'Kepala Sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                            <option value="Wakasis Siswa" {{ request('jabatan') == 'Wakasis Siswa' ? 'selected' : '' }}>Wakasis Siswa</option>
                            <option value="Wakasis Guru" {{ request('jabatan') == 'Wakasis Guru' ? 'selected' : '' }}>Wakasis Guru</option>
                        </select>
                    </div>

                    @if (request('search') || request('jabatan'))
                        <a href="{{ route('admin.guru.index') }}" class="px-3 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-xl text-xs font-semibold flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- TABLE CARD (FIXED SCROLL CONTAINER DENGAN STICKY THEAD) -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden flex flex-col">
                <div class="overflow-x-auto max-h-[calc(100vh-270px)] min-h-[380px] overflow-y-auto">
                    <table class="w-full text-left border-collapse text-xs" id="tableGuru">
                        <thead class="sticky top-0 bg-[#F8FAFC] z-10">
                            <tr class="text-slate-500 font-semibold uppercase tracking-wider border-b border-slate-200">
                                <th class="py-2.5 px-3 text-center w-12">No</th>
                                <th class="py-2.5 px-3">Nama Lengkap & NIP</th>
                                <th class="py-2.5 px-3 w-36">Jabatan / Role</th>
                                <th class="py-2.5 px-3 w-36">No. HP</th>
                                <th class="py-2.5 px-3 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-600" id="guruTbody">
                            @forelse ($guruList as $idx => $g)
                                <tr class="hover:bg-slate-50/60 transition-colors guru-row" data-search="{{ strtolower($g->nama_guru . ' ' . $g->nip . ' ' . $g->jabatan) }}">
                                    <td class="py-2 px-3 text-center font-medium text-slate-400 text-xs">
                                        {{ $guruList->firstItem() + $idx }}
                                    </td>
                                    <td class="py-2 px-3">
                                        <p class="font-bold text-[#1E2538] text-xs leading-tight">{{ $g->nama_guru }}</p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">NIP: {{ $g->nip }}</p>
                                    </td>
                                    <td class="py-2 px-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold 
                                            @if($g->jabatan === 'Kepala Sekolah') bg-emerald-50 text-emerald-700
                                            @elseif(str_contains($g->jabatan, 'Wakasis')) bg-indigo-50 text-indigo-700
                                            @elseif($g->jabatan === 'Guru Piket') bg-amber-50 text-amber-700
                                            @else bg-blue-50 text-blue-700 @endif">
                                            {{ $g->jabatan ?? 'Guru' }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-3 text-xs text-slate-500 font-medium">
                                        {{ $g->no_hp ?: '-' }}
                                    </td>
                                    <td class="py-2 px-3 text-center">
                                        <div class="flex items-center justify-center space-x-1">
                                            <!-- TOMBOL EDIT DATA GURU -->
                                            <button type="button" 
                                                onclick="openEditGuruModal('{{ $g->id_guru }}', '{{ addslashes($g->nama_guru) }}', '{{ $g->nip }}', '{{ $g->no_hp ?? '' }}', '{{ $g->user ? $g->user->role : 'guru' }}')"
                                                class="p-1.5 text-[#405078] hover:bg-[#405078]/10 rounded-lg transition-colors cursor-pointer" title="Edit Data Guru">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                            </button>
                                            <!-- TOMBOL HAPUS GURU -->
                                            <form action="{{ route('admin.guru.destroy', $g->id_guru) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data {{ addslashes($g->nama_guru) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus Guru">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 italic text-xs">
                                        Tidak ada data guru yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION BAR (SESUAI GAMBAR 2) -->
                <x-pagination-bar :paginator="$guruList" />
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT DATA GURU -->
<div id="modalEditGuru" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-2xs flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-md w-full p-5 space-y-3.5">
        <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
            <h3 class="font-bold text-[#1E2538] text-sm flex items-center space-x-1.5">
                <i data-lucide="edit-3" class="w-4 h-4 text-[#405078]"></i>
                <span>Edit Data Pegawai / Guru</span>
            </h3>
            <button type="button" onclick="closeEditGuruModal()" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="formEditGuru" method="POST" class="space-y-3">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">NIP / Kode Pegawai *</label>
                <input type="text" name="nip" id="edit_nip" required
                    class="block w-full px-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078]">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap & Gelar *</label>
                <input type="text" name="nama_guru" id="edit_nama_guru" required
                    class="block w-full px-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078]">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor HP (WhatsApp)</label>
                <input type="text" name="no_hp" id="edit_no_hp" placeholder="Contoh: 081234567890"
                    class="block w-full px-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078]">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Role / Jabatan Sistem *</label>
                <select name="role" id="edit_role" required
                    class="block w-full px-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] font-medium focus:outline-none focus:border-[#405078] cursor-pointer">
                    <option value="guru">Guru Mata Pelajaran</option>
                    <option value="guru_piket">Guru Piket</option>
                    <option value="kepala_sekolah">Kepala Sekolah</option>
                    <option value="wakasis_siswa">Wakil Kesiswaan (Siswa)</option>
                    <option value="wakasis_guru">Wakil Kesiswaan (Guru / SDM)</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Ganti Password (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" placeholder="Masukkan password baru..."
                    class="block w-full px-3 py-1.5 bg-[#F8FAFC] border border-slate-200 rounded-xl text-xs text-[#1E2538] focus:outline-none focus:border-[#405078]">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2.5 border-t border-slate-100">
                <button type="button" onclick="closeEditGuruModal()" class="px-3.5 py-1.5 border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-4 py-1.5 bg-[#405078] hover:bg-[#2F3C5C] text-white rounded-xl text-xs font-bold shadow-xs cursor-pointer">
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
            tableCol.className = 'lg:col-span-3 space-y-3.5';
            btnText.innerText = 'Buka Form Tambah';
            isFormFolded = true;
        } else {
            formCol.style.display = 'block';
            tableCol.className = 'lg:col-span-2 space-y-3.5';
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
