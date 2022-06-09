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
        'status_baca',
        'tanggal_baca',
        'pesan_disposisi',
        'tanggal_disposisi'
    ];
}
