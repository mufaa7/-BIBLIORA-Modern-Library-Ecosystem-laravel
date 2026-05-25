<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // =========================================================================
    // 1. BACK-OFFICE TERMINAL: Halaman Utama Khusus Admin (Dashboard Analytics)
    // =========================================================================
    public function index()
    {
        // Hitung angka agregat untuk kotak informasi statistik utama
        $totalBuku      = Buku::sum('jumlah'); 
        $totalAnggota   = User::where('role', 'user')->count(); 
        $pinjamAktif    = Peminjaman::where('status_peminjaman', 'dipinjam')->count(); 
        $totalDendaMasuk = Peminjaman::whereIn('status_peminjaman', ['kembali', 'lunas'])->sum('denda'); 

        // DATA UNTUK GRAFIK (Chart.js): 5 Buku Paling Populer
        $bukuPopuler = DetailPeminjaman::select('id_buku', DB::raw('SUM(jumlah) as total_dipinjam'))
            ->groupBy('id_buku')
            ->orderBy('total_dipinjam', 'desc')
            ->take(5)
            ->get();

        $chartLabels = [];
        $chartData   = [];

        foreach ($bukuPopuler as $item) {
            $buku = Buku::find($item->id_buku);
            if ($buku) {
                $chartLabels[] = $buku->judul;
                $chartData[]   = (int) $item->total_dipinjam;
            }
        }

        // INJEKSI LOGIKA AKTIVITAS TERBARU (Sirkulasi Real-time)
        $recentActivities = Peminjaman::with(['user', 'details.buku'])
            ->orderBy('updated_at', 'DESC')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBuku', 
            'totalAnggota', 
            'pinjamAktif', 
            'totalDendaMasuk',
            'chartLabels',
            'chartData',
            'recentActivities'
        ));
    }

    // =========================================================================
    // 2. MEMBER HUB TERMINAL: Halaman Utama Dashboard Khusus Anggota/User
    // =========================================================================
    public function userIndex()
    {
        $id_user = Auth::id(); 
        $user    = Auth::user(); 

        // Tarik riwayat manifes sirkulasi milik user ini beserta relasi detail bukunya
        $myLoans = Peminjaman::with('details.buku')
            ->where('id_user', $id_user)
            ->latest()
            ->get();

        // FIXED LOGIC KUOTA: Status 'booking' & 'dipinjam' wajib memotong kuota slot mahasiswa!
        $bukuSedangDipinjam = DetailPeminjaman::whereHas('peminjaman', function($query) use ($id_user) {
            $query->where('id_user', $id_user)
                  ->whereIn('status_peminjaman', ['dipinjam', 'booking']);
        })->sum('jumlah');

        // SINKRONISASI KUOTA INTERFACES (Maksimal Kuota Pinjaman Universitas Adalah 3 Buku)
        $maxKuota = 3;
        $sisaKuota = max(0, $maxKuota - $bukuSedangDipinjam);

        // KONTROL KALKULASI DENDA BERJALAN SECARA REAL-TIME (SINKRON ATURAN BISNIS)
        $runningFineTotal = 0;
        foreach($myLoans as $loan) {
            if($loan->status_peminjaman === 'dipinjam') {
                // Hanya hitung denda berjalan jika sudah resmi diserahkan ('dipinjam') dan lewat jatuh tempo
                if(Carbon::today()->gt(Carbon::parse($loan->jatuh_tempo))) {
                    $hari = Carbon::today()->diffInDays(Carbon::parse($loan->jatuh_tempo));
                    $qtyBuku = $loan->details->sum('jumlah');
                    $runningFineTotal += ($hari * 1000 * $qtyBuku);
                }
            } elseif($loan->status_peminjaman === 'kembali') {
                // Jika sudah dikembalikan tapi dendanya durhaka belum dibayar ke kasir, akumulasikan terus
                $runningFineTotal += $loan->denda;
            }
        }

        // ENGINE REKOMENDASI SIDEBAR & DASHBOARD: Ambil 4 Buku Paling Laris Berdasarkan Detail Transaksi
        $topBooks = Buku::with('kategori')
            ->withCount(['details as total_dipinjam' => function($query) {
                $query->select(DB::raw('SUM(jumlah)'));
            }])
            ->orderBy('total_dipinjam', 'desc')
            ->take(4)
            ->get();

        // Loloskan semua variabel krusial ke blade agar ekosistem interaksi tidak crash
        return view('user.dashboard', compact(
            'user', 
            'myLoans', 
            'bukuSedangDipinjam', 
            'sisaKuota', 
            'runningFineTotal', 
            'topBooks'
        ));
    }

    // =========================================================================
    // 3. PUBLIC SECURITY POS: Endpoint Gerbang Verifikasi Validasi Struk Fisik
    // =========================================================================
    public function verifyReceipt($id_peminjaman)
    {
        // Cari data transaksi berdasarkan ID beserta data user dan detail buku di dalamnya
        $peminjaman = Peminjaman::with(['user', 'details.buku'])->find($id_peminjaman);

        // Jika ID transaksi manipulasi atau tidak ada di database, lempar eror 404 publik
        if (!$peminjaman) {
            abort(404, 'Transaction record not found in Bibliora database.');
        }

        // Return ke halaman publik verifikasi yang mandiri dan bersih
        return view('admin.verify_receipt', compact('peminjaman'));
    }

    // =========================================================================
    // 4. INTERACTION PIPELINE: Aksi Perpanjangan Mandiri Dinamis (1, 3, 7 Hari) oleh Mahasiswa
    // =========================================================================
    public function perpanjangMasaPinjam(Request $request, $id)
    {
        // 1. Validasi input durasi perpanjangan online dari dropdown mahasiswa
        $request->validate([
            'durasi' => 'required|integer|in:1,3,7'
        ]);

        $id_user = auth()->id();
        
        // PENGAMAN DATABASE: Ambil data paling fresh langsung dengan mengunci baris row (Pessimistic Locking)
        $peminjaman = Peminjaman::where('id_peminjaman', $id)
            ->where('id_user', $id_user)
            ->lockForUpdate()
            ->firstOrFail();

        // PROTEKSI 1: Wajib berstatus 'dipinjam'
        if ($peminjaman->status_peminjaman !== 'dipinjam') {
            return redirect()->back()->with('error', 'Gagal! Hanya buku yang sedang aktif dipinjam yang dapat diperpanjang.');
        }

        // PROTEKSI 2: Kunci sistem online jika telat sehari saja!
        if (Carbon::today()->gt(Carbon::parse($peminjaman->jatuh_tempo))) {
            return redirect()->back()->with('error', '⚠️ Perpanjangan Ditolak! Buku ini sudah melewati jatuh tempo (Overdue). Silakan kembalikan ke perpustakaan dan selesaikan denda terlebih dahulu.');
        }

        // PROTEKSI 3: Batasan Aturan Bisnis Maksimal Perpanjangan Mandiri Online 1 Kali
        if ($peminjaman->jumlah_perpanjangan >= 1) {
            return redirect()->back()->with('error', 'Gagal! Batas perpanjangan mandiri untuk transaksi ini sudah habis (Maksimal 1 kali).');
        }

        try {
            DB::transaction(function () use ($peminjaman, $request) {
                // Hitung penambahan tanggal jatuh tempo baru dari tanggal lama
                $jatuhTempoBaru = Carbon::parse($peminjaman->jatuh_tempo)->addDays($request->durasi)->toDateString();

                // CODES FIX: Kunci mati nilai counter ke angka 1 untuk memutus celah bypass spam click
                $peminjaman->update([
                    'jatuh_tempo'         => $jatuhTempoBaru,
                    'jumlah_perpanjangan' => 1
                ]);
            });

            return redirect()->route('user.dashboard')->with('success', 'Masa pinjam buku berhasil diperpanjang ' . $request->durasi . ' hari ke depan!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses perpanjangan.');
        }
    }
}