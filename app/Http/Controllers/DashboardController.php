<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung angka agregat untuk kotak informasi statistik utama
        $totalBuku      = Buku::sum('jumlah'); // Total fisik seluruh stok buku
        $totalAnggota   = User::count(); // Total member terdaftar
        $pinjamAktif    = Peminjaman::where('status_peminjaman', 'dipinjam')->count(); // Transaksi menggantung
        
        // Total uang kas denda yang sudah sukses dibayarkan (bernilai 0 tapi status kembali)
        // Atau menghitung total denda yang pernah ditarik dari laporan sirkulasi lunas
        $totalDendaMasuk = Peminjaman::where('status_peminjaman', 'kembali')->sum('denda'); 

        // 2. DATA UNTUK GRAFIK (Chart.js): 5 Buku Paling Populer (Paling Sering Dipinjam)
        // Menggabungkan tabel detail_peminjamans dengan bukus untuk menghitung ranking sirkulasi
        $bukuPopuler = DetailPeminjaman::select('id_buku', DB::raw('SUM(jumlah) as total_dipinjam'))
            ->groupBy('id_buku')
            ->orderBy('total_dipinjam', 'desc')
            ->take(5)
            ->get();

        // Siapkan array kosong untuk menampung label judul dan data angka grafik
        $chartLabels = [];
        $chartData   = [];

        foreach ($bukuPopuler as $item) {
            // Ambil judul buku berdasarkan relasi ID-nya
            $buku = Buku::find($item->id_buku);
            if ($buku) {
                $chartLabels[] = $buku->judul;
                $chartData[]   = (int) $item->total_dipinjam;
            }
        }

        // 3. Lempar semua variabel data asli ke dalam halaman view dashboard admin
        return view('admin.dashboard', compact(
            'totalBuku', 
            'totalAnggota', 
            'pinjamAktif', 
            'totalDendaMasuk',
            'chartLabels',
            'chartData'
        ));
    }
}