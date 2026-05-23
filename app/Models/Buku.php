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

    // Relasi: Buku ini dimiliki oleh sebuah Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
}