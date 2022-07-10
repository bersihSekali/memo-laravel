<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'satuan_kerja',
        'departemen',
        'level',
        'password',
        'cabang',
        'bidang_cabang'
    ];

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja');
    }

    public function departemenTable()
    {
        return $this->belongsTo(Departemen::class, 'departemen');
    }

    public function levelTable()
    {
        return $this->belongsTo(Level::class, 'level');
    }

    public function cabangTable()
    {
        return $this->belongsTo(Cabang::class, 'cabang');
    }

    public function bidangCabangTable()
    {
        return $this->belongsTo(BidangCabang::class, 'bidang_cabang');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function satuanKerjaTable()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja');
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        // Return email address only...
        return $this->email;
    }
}
