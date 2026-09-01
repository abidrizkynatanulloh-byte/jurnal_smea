<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IzinGuru extends Model
{
    use SoftDeletes;

    protected $table = 'izin_guru';

    protected $fillable = [
        'id_guru',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'keterangan',
        'bukti_foto',
        'kelas_terdampak',
        'status_waka',
        'status_sdm',
        'status_piket',
        'status_kepsek',
        'status_akhir',
        'catatan_penolakan',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    /**
     * Cek jika ketiga role (Waka, Piket, Kepsek) telah menyetujui, maka status_akhir menjadi 'Disetujui'.
     */
    public function cekDanUpdateStatusAkhir()
    {
        if ($this->status_waka === 'Ditolak' || $this->status_piket === 'Ditolak' || $this->status_kepsek === 'Ditolak') {
            $this->status_akhir = 'Ditolak';
        } elseif ($this->status_waka === 'Disetujui' && $this->status_piket === 'Disetujui' && $this->status_kepsek === 'Disetujui') {
            $this->status_akhir = 'Disetujui';
        } else {
            $this->status_akhir = 'Diajukan';
        }
        $this->save();
    }
}
