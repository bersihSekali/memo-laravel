<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TujuanSatuanKerja extends Model
{
    use HasFactory;

    protected $fillable = [
        'satuan_kerja_id',
        'memo_id',
        'all_flag',
        'status_baca',
        'tanggal_baca'
    ];

    public function tujuanSatuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_id');
    }
}
