<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    // Hubungkan dengan nama tabel yang benar di database
    protected $table = 'kategoris';

    // Beritahu Laravel kalau primary key-nya bukan 'id'
    protected $primaryKey = 'id_kategori';

    // Kolom yang diizinkan untuk diisi massal via Controller
    protected $fillable = ['nama_kategori', 'keterangan'];

    // Relasi: Satu kategori bisa memiliki banyak buku
    public function bukus()
    {
        return $this->hasMany(Buku::class, 'id_kategori', 'id_kategori');
    }
}