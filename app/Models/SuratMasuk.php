<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'otor_by',
        'otor_status',
        'created_by',
        'nomor_surat',
        'perihal',
        'satuan_kerja_asal',
        'departemen_asal',
        'satuan_kerja_tujuan',
        'departemen_tujuan',
        'lampiran',
        'checker',
        'tanggal_disposisi',
        'satuan_kerja_tujuan_disposisi',
        'departemen_tujuan_disposisi',
        'pesan_disposisi',
        'lampiran_disposisi',
        'tanggal_selesai',
        'status',
    ];

    public function satuanKerjaAsal() {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_asal');
    }

    public function satuanKerjaTujuan() {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_tujuan');
    }

    public function departemenAsal() {
        return $this->belongsTo(Departemen::class, 'departemen_asal');
    }

    public function departemenTujuan() {
        return $this->belongsTo(Departemen::class, 'departemen_tujuan');
    }
}
