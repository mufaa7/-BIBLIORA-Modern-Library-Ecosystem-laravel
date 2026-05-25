<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController; 

/*
|--------------------------------------------------------------------------
| Web Routes - BIBLIO HUB (Perpustakaan Universitas BSI)
|--------------------------------------------------------------------------
*/

// =========================================================================
// GATE 1: JALUR KHUSUS GUEST (Hanya Bisa Diakses Sebelum Login)
// =========================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin']);
});

// =========================================================================
// GATE 2: GLOBAL AUTH (Wajib Login, Berlaku untuk Semua Akun)
// =========================================================================
Route::middleware('auth')->group(function () {
    
    // 0. Aksi Universal Keluar Sistem (Logout)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // =====================================================================
    // TERMINAL USER / MEMBER: Jalur Khusus Mahasiswa
    // =====================================================================
    Route::get('/user/dashboard', [DashboardController::class, 'userIndex'])->name('user.dashboard');
    
    // Rute Book Catalog untuk User Biasa (Menampilkan Data & Filter)
    Route::get('/user/buku', [BukuController::class, 'userIndex'])->name('user.buku.index');
    
    // FIXED INTERACTION PIPELINE: Rute Aksi Booking Mandiri oleh Mahasiswa
    Route::post('/user/buku/booking/{id_buku}', [BukuController::class, 'storeBooking'])->name('user.buku.booking');

    // FIXED EXTENSION VALVE: Rute Aksi Perpanjangan Mandiri 7 Hari oleh Mahasiswa
    Route::post('/user/peminjaman/perpanjang/{id}', [DashboardController::class, 'perpanjangMasaPinjam'])->name('user.peminjaman.perpanjang');

    // =====================================================================
    // CORE SYSTEM ADMIN: Seluruh Operasional Utama Perpustakaan
    // =====================================================================
    
    // 1. Halaman Utama / Dashboard Analytics Admin
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Route CRUD Data Master Admin (Kategori Buku)
    Route::resource('kategori', KategoriController::class);

    // 3. Route CRUD Data Master Admin (Buku)
    Route::resource('buku', BukuController::class);

    // 4. Route Manajemen Anggota (Pendaftaran User Baru)
    Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
    Route::get('/pendaftaran/cetak-kartu/{id}', [PendaftaranController::class, 'cetakKartu'])->name('pendaftaran.cetak_kartu');
    
    // Menyambungkan form upload foto instan dari baris tabel admin ke controller
    Route::post('/pendaftaran/update-avatar', [PendaftaranController::class, 'updateAvatarByAdmin'])->name('user.update_avatar_admin');

    // 5. Modul Laporan, Pembayaran Denda, & Transaksi Peminjaman Multi-Buku
    Route::get('/peminjaman/laporan', [PeminjamanController::class, 'halamanLaporan'])->name('laporan.index');
    Route::post('/peminjaman/laporan/cetak', [PeminjamanController::class, 'cetakLaporan'])->name('laporan.cetak');

    // Rute Resource Sirkulasi Utama Admin
    Route::resource('peminjaman', PeminjamanController::class)->except(['edit', 'update', 'destroy']);

    // Rute Aksi Operasional (Pengembalian, Cetak Struk, dan Pelunasan Denda)
    Route::post('/peminjaman/kembalikan/{id}', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::post('/peminjaman/{id}/cetak', [PeminjamanController::class, 'cetak'])->name('peminjaman.cetak');
    Route::post('/peminjaman/bayar-denda/{id}', [PeminjamanController::class, 'bayarDenda'])->name('peminjaman.bayar_denda');
    
    // FIXED BACK-OFFICE VALVE: Verifikasi fisik penyerahan buku dari status booking ke dipinjam
    Route::post('/peminjaman/konfirmasi-booking/{id}', [PeminjamanController::class, 'konfirmasiBooking'])->name('peminjaman.konfirmasi_booking');

    // 6. ROUTE API INTERNAL: Dipanggil oleh JavaScript AJAX Shimmer Loader
    Route::get('/api/user/{id}', function($id) {
        $user = \App\Models\User::where('id_user', $id)->first();
        
        if ($user) {
            $isBlacklisted = \App\Models\Peminjaman::where('id_user', $id)
                ->where(function($query) {
                    $query->where(function($q) {
                        $q->where('status_peminjaman', 'dipinjam')
                          ->where('jatuh_tempo', '<', \Carbon\Carbon::today()->toDateString());
                    })
                    ->orWhere(function($q) {
                        $q->where('status_peminjaman', 'kembali')
                          ->where('denda', '>', 0);
                    });
                })
                ->exists();

            return response()->json([
                'success'        => true,
                'name'           => $user->name,
                'username'       => $user->username,
                'is_blacklisted' => $isBlacklisted 
            ]);
        }
        
        return response()->json(['success' => false]);
    })->name('api.user.search');

});

// =========================================================================
// GATE 3: PUBLIC ROUTES (Bisa Diakses Siapa Saja Tanpa Login / Bebas Scan HP)
// =========================================================================
Route::get('/verify/receipt/{id_peminjaman}', [DashboardController::class, 'verifyReceipt'])->name('receipt.verify');