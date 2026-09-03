@extends('layouts.app')

@section('title', 'Master Data Guru & Pegawai - Jurnal Esemkita')

@section('content')
<div class="space-y-4">
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center space-x-2">
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Data Guru & Pegawai</h1>
                <span class="px-2.5 py-0.5 text-xs font-semibold bg-slate-200 text-slate-700 rounded-full">
                    {{ $totalGuru }} Pendidik & Staf
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Kelola data seluruh pendidik, jabatan operasional, dan akun sistem</p>
        </div>
        <div class="flex items-center space-x-2">
            <button type="button" onclick="toggleFormPanel()" id="btnToggleForm"
                class="px-3.5 py-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs cursor-pointer">
                <i data-lucide="layout-grid" class="w-3.5 h-3.5 text-slate-600"></i>
                <span id="btnToggleFormText">Sembunyikan Form Tambah</span>
            </button>
            <a href="{{ route('admin.guru.trash') }}" class="px-3.5 py-1.5 border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 shadow-2xs">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                <span>Sampah</span>
            </a>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start" id="masterDataGrid">
        <!-- BAGIAN 1: FORM TAMBAH GURU / PEGAWAI BARU (STICKY & FOLDABLE) -->
        <div id="formCol" class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-3.5 lg:sticky lg:top-18 z-10">
            <div class="flex items-center space-x-2 pb-2.5 border-b border-slate-100">
                <div class="w-6 h-6 rounded bg-[#1E293B] text-white flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Tambah Pegawai Baru</h3>
            </div>

            <form action="{{ route('admin.guru.store') }}" method="POST" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">NIP / Kode Pegawai *</label>
                    <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Contoh: 198001012005011001" required
                        class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B] focus:ring-1 focus:ring-[#1E293B]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap & Gelar *</label>
                    <input type="text" name="nama_guru" value="{{ old('nama_guru') }}" placeholder="Contoh: Drs. Budi Santoso, M.Pd" required
                        class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B] focus:ring-1 focus:ring-[#1E293B]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor HP (WhatsApp)</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890"
                        class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Role / Jabatan Sistem *</label>
                    <select name="role" required
                        class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-[#1E293B] cursor-pointer">
                        <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru Mata Pelajaran</option>
                        <option value="guru_piket" {{ old('role') == 'guru_piket' ? 'selected' : '' }}>Guru Piket</option>
                        <option value="kepala_sekolah" {{ old('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                        <option value="wakasis_siswa" {{ old('role') == 'wakasis_siswa' ? 'selected' : '' }}>Wakil Kesiswaan (Siswa)</option>
                        <option value="wakasis_guru" {{ old('role') == 'wakasis_guru' ? 'selected' : '' }}>Wakil Kesiswaan (Guru / SDM)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Password Akun Login *</label>
                    <input type="password" name="password" placeholder="Minimal 4 karakter" required
                        class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B]">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 px-4 bg-[#1E293B] hover:bg-[#0F172A] text-white rounded-lg text-xs font-bold transition-all shadow-xs flex items-center justify-center space-x-1.5 cursor-pointer">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        <span>Simpan Pegawai</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: DAFTAR & LIVE FILTER GURU (DATA CARD) -->
        <div id="tableCol" class="lg:col-span-2 space-y-3.5">
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden flex flex-col">
                <!-- Top Control Bar (Search, Filter, Counter) -->
                <div class="p-3.5 border-b border-slate-200 bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <form action="{{ route('admin.guru.index') }}" method="GET" class="flex flex-1 flex-col sm:flex-row items-center gap-2.5">
                        <div class="relative flex-1 w-full">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="search" id="liveSearchGuru" value="{{ request('search') }}" placeholder="Cari Nama, NIP, atau Jabatan..." 
                                class="block w-full pl-9 pr-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E293B] focus:ring-1 focus:ring-[#1E293B] transition-all">
                        </div>

                        <div class="w-full sm:w-44">
                            <select name="jabatan" onchange="this.form.submit()" class="block w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 font-medium focus:outline-none focus:border-[#1E293B] cursor-pointer">
                                <option value="">Semua Jabatan</option>
                                <option value="Guru" {{ request('jabatan') == 'Guru' ? 'selected' : '' }}>Guru Mapel</option>
                                <option value="Guru Piket" {{ request('jabatan') == 'Guru Piket' ? 'selected' : '' }}>Guru Piket</option>
                                <option value="Kepala Sekolah" {{ request('jabatan') == 'Kepala Sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                <option value="Wakasis Siswa" {{ request('jabatan') == 'Wakasis Siswa' ? 'selected' : '' }}>Wakasis Siswa</option>
                                <option value="Wakasis Guru" {{ request('jabatan') == 'Wakasis Guru' ? 'selected' : '' }}>Wakasis Guru</option>
                            </select>
                        </div>

                        @if (request('search') || request('jabatan'))
                            <a href="{{ route('admin.guru.index') }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-semibold transition-colors">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Scrollable Table Body -->
                <div class="overflow-x-auto max-h-[calc(100vh-270px)] min-h-[400px] overflow-y-auto">
                    <table class="w-full text-left border-collapse text-xs" id="tableGuru">
                        <thead class="sticky top-0 bg-slate-50 border-b border-slate-200 z-10">
                            <tr class="text-slate-600 font-bold uppercase tracking-wider text-[11px]">
                                <th class="py-3 px-3.5 text-center w-12">No</th>
                                <th class="py-3 px-3.5">Nama Lengkap & NIP</th>
                                <th class="py-3 px-3.5 w-40">Jabatan / Role</th>
                                <th class="py-3 px-3.5 w-36">No. HP (WhatsApp)</th>
                                <th class="py-3 px-3.5 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700" id="guruTbody">
                            @forelse ($guruList as $idx => $g)
                                <tr class="hover:bg-slate-50/80 transition-colors guru-row" data-search="{{ strtolower($g->nama_guru . ' ' . $g->nip . ' ' . $g->jabatan) }}">
                                    <td class="py-2.5 px-3.5 text-center font-medium text-slate-400 text-xs tabular-nums">
                                        {{ $guruList->firstItem() + $idx }}
                                    </td>
                                    <td class="py-2.5 px-3.5">
                                        <p class="font-bold text-slate-900 text-xs leading-tight">{{ $g->nama_guru }}</p>
                                        <p class="text-[11px] text-slate-500 font-mono tabular-nums mt-0.5">NIP: {{ $g->nip }}</p>
                                    </td>
                                    <td class="py-2.5 px-3.5">
                                        <span class="inline-block px-2.5 py-0.5 rounded text-[11px] font-semibold border
                                            @if($g->jabatan === 'Kepala Sekolah') bg-emerald-50 text-emerald-800 border-emerald-200
                                            @elseif(str_contains($g->jabatan, 'Wakasis')) bg-indigo-50 text-indigo-800 border-indigo-200
                                            @elseif($g->jabatan === 'Guru Piket') bg-amber-50 text-amber-800 border-amber-200
                                            @else bg-slate-100 text-slate-700 border-slate-200 @endif">
                                            {{ $g->jabatan ?? 'Guru' }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3.5 text-xs text-slate-600 font-mono tabular-nums">
                                        {{ $g->no_hp ?: '-' }}
                                    </td>
                                    <td class="py-2.5 px-3.5 text-center">
                                        <div class="flex items-center justify-center space-x-1.5">
                                            <button type="button" 
                                                onclick="openEditGuruModal('{{ $g->id_guru }}', '{{ addslashes($g->nama_guru) }}', '{{ $g->nip }}', '{{ $g->no_hp ?? '' }}', '{{ $g->user ? $g->user->role : 'guru' }}')"
                                                class="w-7 h-7 rounded border border-slate-200 hover:border-slate-300 hover:bg-slate-100 text-slate-600 hover:text-slate-900 flex items-center justify-center transition-colors cursor-pointer" title="Edit Data Guru">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                            </button>
                                            <form action="{{ route('admin.guru.destroy', $g->id_guru) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data {{ addslashes($g->nama_guru) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-7 h-7 rounded border border-slate-200 hover:border-rose-300 hover:bg-rose-50 text-slate-600 hover:text-rose-600 flex items-center justify-center transition-colors cursor-pointer" title="Hapus Guru">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-400 italic text-xs">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                        Tidak ada data guru yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION BAR (SESUAI GAMBAR 5) -->
                <x-pagination-bar :paginator="$guruList" />
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT DATA GURU -->
<div id="modalEditGuru" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center hidden p-4">
    <div class="bg-white border border-slate-200 rounded-xl shadow-xl max-w-md w-full p-5 space-y-3.5">
        <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
            <h3 class="font-bold text-slate-900 text-sm flex items-center space-x-1.5">
                <i data-lucide="edit-3" class="w-4 h-4 text-slate-700"></i>
                <span>Edit Data Pegawai / Guru</span>
            </h3>
            <button type="button" onclick="closeEditGuruModal()" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="formEditGuru" method="POST" class="space-y-3">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">NIP / Kode Pegawai *</label>
                <input type="text" name="nip" id="edit_nip" required
                    class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-[#1E293B]">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap & Gelar *</label>
                <input type="text" name="nama_guru" id="edit_nama_guru" required
                    class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-[#1E293B]">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor HP (WhatsApp)</label>
                <input type="text" name="no_hp" id="edit_no_hp" placeholder="Contoh: 081234567890"
                    class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-[#1E293B]">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Role / Jabatan Sistem *</label>
                <select name="role" id="edit_role" required
                    class="block w-full px-2.5 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-medium focus:outline-none focus:border-[#1E293B] cursor-pointer">
                    <option value="guru">Guru Mata Pelajaran</option>
                    <option value="guru_piket">Guru Piket</option>
                    <option value="kepala_sekolah">Kepala Sekolah</option>
                    <option value="wakasis_siswa">Wakil Kesiswaan (Siswa)</option>
                    <option value="wakasis_guru">Wakil Kesiswaan (Guru / SDM)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Ganti Password (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" placeholder="Masukkan password baru..."
                    class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-[#1E293B]">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2.5 border-t border-slate-100">
                <button type="button" onclick="closeEditGuruModal()" class="px-3.5 py-1.5 border border-slate-300 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 cursor-pointer">
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
