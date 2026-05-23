<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';
    protected $primaryKey = 'id_peminjaman';

    protected $fillable = [
        'id_user', 
        'tanggal_pinjam', 
        'tanggal_kembali', 
        'jatuh_tempo', 
        'status_peminjaman', 
        'denda'
    ];

    // Relasi: Peminjaman ini dilakukan oleh satu User/Anggota
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi: Satu transaksi peminjaman bisa punya banyak item buku di detailnya
    public function details()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }
}