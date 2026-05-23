<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjamans';
    protected $primaryKey = 'id_detail';

    protected $fillable = ['id_peminjaman', 'id_buku', 'jumlah', 'sub_total'];

    // Relasi kembali ke transaksi induknya
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    // Relasi untuk tahu buku apa yang ada di detail ini
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }
}