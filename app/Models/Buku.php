<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus';
    protected $primaryKey = 'id_buku';

    protected $fillable = [
        'id_kategori', 
        'kode_buku', 
        'judul', 
        'pengarang', 
        'penerbit', 
        'tahun_terbit', 
        'jumlah', 
        'lokasi_rak', 
        'status'
    ];

    // 1. Relasi: Buku ini dimiliki oleh sebuah Kategori (Many to One)
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    // 2. FIXED RELASI: Menyambungkan Buku ke Tabel Detail Transaksi (One to Many)
    public function details()
    {
        // Parameter 2: 'id_buku' adalah nama kolom foreign key di tabel detail_peminjamans
        // Parameter 3: 'id_buku' adalah nama kolom primary key di tabel bukus
        return $this->hasMany(DetailPeminjaman::class, 'id_buku', 'id_buku');
    }
}