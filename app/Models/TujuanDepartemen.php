<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TujuanDepartemen extends Model
{
    use HasFactory;

    protected $fillable = [
        'departemen_id',
        'memo_id',
        'status_baca',
        'tanggal_baca',
        'pesan_disposisi',
        'tanggal_disposisi'
    ];
}
