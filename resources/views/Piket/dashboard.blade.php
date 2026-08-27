<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Guru Piket</title>
</head>
<body>

    <h1>Dashboard Guru Piket</h1>
    <p>Halo, <b>{{ Auth::user()->username }}</b> (Guru Piket)</p>

    {{-- Tombol Logout --}}
    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit">Keluar (Logout)</button>
    </form>
    <hr>

    {{-- Notifikasi Sukses / Error --}}
    @if (session('success'))
        <p style="color: green;"><b>{{ session('success') }}</b></p>
    @endif

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ========================================================== -->
    <!-- BAGIAN 1: FORM INPUT PENGAJUAN DISPEN SISWA               -->
    <!-- ========================================================== -->
    <fieldset style="margin-bottom: 25px;">
        <legend><h3>📝 Input Pengajuan Dispensasi Siswa</h3></legend>

        <form action="{{ route('piket.dispen.store') }}" method="POST">
            @csrf

            {{-- 1. Pilih Siswa --}}
            <div>
                <label for="nis">Pilih Siswa yang Datang:</label><br>
                <select name="nis" id="nis" required style="width: 350px;">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach ($daftarSiswa as $s)
                        <option value="{{ $s->nis }}" {{ old('nis') == $s->nis ? 'selected' : '' }}>
                            {{ $s->nis }} - {{ $s->nama_siswa }}
                        </option>
                    @endforeach
                </select>
            </div>
            <br>

            {{-- 2. Alasan / Keperluan Dispen --}}
            <div>
                <label for="keperluan">Alasan / Keperluan Dispensasi:</label><br>
                <textarea name="keperluan" id="keperluan" rows="3" cols="45" placeholder="Contoh: Mengikuti lomba olimpiade matematika di dinas pendidikan" required>{{ old('keperluan') }}</textarea>
            </div>
            <br>

            {{-- 3. Jam Rencana Keluar & Kembali --}}
            <div>
                <label for="jam_keluar_rencana">Rencana Jam Keluar:</label>
                <input type="time" name="jam_keluar_rencana" id="jam_keluar_rencana" required>

                &nbsp;&nbsp;&nbsp;

                <label for="jam_kembali_rencana">Rencana Jam Kembali (Opsional):</label>
                <input type="time" name="jam_kembali_rencana" id="jam_kembali_rencana">
            </div>
            <br>

            {{-- Tombol Kirim ke Kesiswaan --}}
            <button type="submit" style="padding: 6px 15px; font-weight: bold; cursor: pointer;">
                🚀 Kirim Pengajuan ke Kesiswaan (ACC)
            </button>
        </form>
    </fieldset>

    <!-- ========================================================== -->
    <!-- BAGIAN 2: STATUS DISPEN HARI INI                          -->
    <!-- ========================================================== -->
    <h3>📋 Daftar Pengajuan Dispensasi Hari Ini</h3>

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr bgcolor="#f0f0f0">
                <th>No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Jam Keluar / Kembali</th>
                <th>Keperluan</th>
                <th>Status Persetujuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dispenHariIni as $index => $d)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $d->nis }}</td>
                    <td><b>{{ $d->siswa ? $d->siswa->nama_siswa : '-' }}</b></td>
                    <td>
                        Keluar: {{ $d->jam_keluar_rencana ?? '-' }} <br>
                        Kembali: {{ $d->jam_kembali_rencana ?? 'Tidak kembali' }}
                    </td>
                    <td>{{ $d->keperluan }}</td>
                    <td>
                        @if ($d->status === 'Menunggu')
                            <span style="color: orange; font-weight: bold;">⏳ Menunggu ACC Wakasis</span>
                        @elseif ($d->status === 'Disetujui')
                            <span style="color: green; font-weight: bold;">✓ Disetujui Kesiswaan</span>
                        @else
                            <span style="color: red; font-weight: bold;">✕ Ditolak Kesiswaan</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" align="center"><i>Belum ada pengajuan dispen hari ini.</i></td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>