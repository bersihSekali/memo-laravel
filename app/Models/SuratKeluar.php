<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratKeluar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'otor2_by',
        'otor1_by',
        'otor2_by_pengganti',
        'otor1_by_pengganti',
        'created_by',
        'deleted_by',
        'nomor_surat',
        'perihal',
        'satuan_kerja_asal',
        'departemen_asal',
        'lampiran',
        'tanggal_otor2',
        'tanggal_otor1',
        'status',
        'internal',
        'no_urut'
    ];

    public function satuanKerjaAsal()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_asal');
    }

    public function departemenAsal()
    {
        return $this->belongsTo(Departemen::class, 'departemen_asal');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function otor2By()
    {
        return $this->belongsTo(User::class, 'otor2_by');
    }

    public function otor2ByPengganti()
    {
        return $this->belongsTo(User::class, 'otor2_by_pengganti');
    }

    public function otor1By()
    {
        return $this->belongsTo(User::class, 'otor1_by');
    }

    public function otor1ByPengganti()
    {
        return $this->belongsTo(User::class, 'otor1_by_pengganti');
    }

    public function tujuanSatker()
    {
        return $this->belongsTo(TujuanSatuanKerja::class);
    }

    public function tujuanDepartemen()
    {
        return $this->belongsTo(TujuanSatuanKerja::class);
    }

    public function forward()
    {
        return $this->belongsTo(Forward::class);
    }
}
