<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // 1. Primary key asli lu adalah id_user
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     */
    // 2. Daftarkan field sesuai file migrasi (name, username, email, dll)
    protected $fillable = [
        'id_user',
        'name',
        'username',
        'email',
        'password',
        'no_telp',
        'alamat',
        'role',
        'status_aktif',
        'foto_profil',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Mengikuti enkripsi bawaan Laravel
        'status_aktif' => 'boolean', // Memastikan dibaca sebagai true/false atau 1/0
    ];

    /**
     * ACCESSOR: Mengubah id_user angka menjadi format 5 digit (00001)
     * Panggil di Blade dengan: $member->id_user_formatted
     */
    protected function idUserFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => str_pad($this->id_user, 5, '0', STR_PAD_LEFT),
        );
    }
}