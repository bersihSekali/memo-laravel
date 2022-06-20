<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'aktifitas',
        'deskripsi',
        'url',
        'ip_address',
        'mac_address',
        'user_agent'
    ];

    public function userID()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function suratTable()
    {
        return $this->belongsTo(SuratKeluar::class, 'deskripsi');
    }
}
