<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TujuanDepartemen extends Model
{
    use HasFactory;

    protected $fillable = [
        'memo_id',
        'departemen_id',
        'all_flag',
        'status_baca',
        'tanggal_baca',
        'pesan_disposisi',
        'tanggal_disposisi'
    ];

    public function tujuanDepartemen()
    {
        return $this->belongsTo(Departemen::class, 'departemen_id');
    }
}
