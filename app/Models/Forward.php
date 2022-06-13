<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forward extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'memo_id',
        'status_baca',
        'tanggal_baca',
        'pesan_disposisi',
        'tanggal_disposisi'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
