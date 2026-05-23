<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\DetailPeminjaman; 
use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PeminjamanController extends Controller
{
    // 1. Menampilkan semua riwayat transaksi peminjaman (Kebutuhan Fungsional)
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'details.buku'])->latest()->get();
        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    // 2. PROSES MENCATAT PEMINJAMAN MULTI-BUKU + VALIDASI KUOTA MAKSIMAL SERVER-SIDE + BLACKLIST OVERDUE
    public function store(Request $request)
    {
        // Validasi input: id_buku dan jumlah WAJIB berbentuk array
        $request->validate([
            'id_user'   => 'required|exists:users,id_user',
            'id_buku'   => 'required|array|min:1|max:3', // Minimal 1 buku, maksimal 3 buku dalam satu form
            'id_buku.*' => 'required|exists:bukus,id_buku', 
            'jumlah'    => 'required|array',
            'jumlah.*'  => 'required|integer|min:1',
            'durasi'    => 'required|integer|in:1,3,7',
        ]);

        // Proteksi tambahan: Cek apakah ada input ID buku yang duplikat dalam satu form
        if (count($request->id_buku) !== count(array_unique($request->id_buku))) {
            return redirect()->back()->with('error', 'Gagal! Ada judul buku yang duplikat dalam form input.')->withInput();
        }

        // ==================== PROTEKSI 1: AUTOMATIC BLACKLIST CHECK ====================
        // Blokir transaksi jika user punya buku yang masih dipinjam dan MELEWATI jatuh tempo hari ini
        $punyaTunggakanOverdue = Peminjaman::where('id_user', $request->id_user)
            ->where('status_peminjaman', 'dipinjam')
            ->where('jatuh_tempo', '<', Carbon::today()->toDateString())
            ->exists();

        if ($punyaTunggakanOverdue) {
            return redirect()->back()
                ->with('error', '⚠️ TRANSAKSI DITOLAK! Anggota ini ditangguhkan (Blacklist) karena memiliki pinjaman buku yang telah melewati jatuh tempo. Selesaikan tunggakan terlebih dahulu!')
                ->withInput();
        }

        // ==================== PROTEKSI 2: VALIDASI KUOTA OPSI 3 ====================
        // Hitung total buku yang belum dikembalikan oleh user ini di database
        $bukuSedangDipinjam = DetailPeminjaman::whereHas('peminjaman', function($query) use ($request) {
            $query->where('id_user', $request->id_user)
                  ->where('status_peminjaman', 'dipinjam');
        })->sum('jumlah');

        $bukuBaruAkanDipinjam = array_sum($request->jumlah);

        // Jika total pinjaman lama + baru melebihi 3, batalkan transaksi
        if (($bukuSedangDipinjam + $bukuBaruAkanDipinjam) > 3) {
            $sisaKuota = 3 - $bukuSedangDipinjam;
            return redirect()->back()->with('error', "Transaksi Ditolak! Anggota ini sudah mengantongi {$bukuSedangDipinjam} buku yang belum dikembalikan. Sisa batas kuota pinjam saat ini: {$sisaKuota} buku.") ->withInput();
        }
        // ===========================================================================

        try {
            DB::transaction(function () use ($request) {
                $tanggalPinjam = Carbon::now()->toDateString();
                $jatuhTempo = Carbon::now()->addDays($request->durasi)->toDateString();

                // A. Simpan ke Tabel Induk (Master Peminjaman)
                $peminjaman = Peminjaman::create([
                    'id_user'           => $request->id_user,
                    'tanggal_pinjam'    => $tanggalPinjam,
                    'jatuh_tempo'       => $jatuhTempo,
                    'status_peminjaman' => 'dipinjam',
                    'denda'             => 0
                ]);

                // B. Perulangan untuk menyimpan setiap buku ke Tabel Detail
                foreach ($request->id_buku as $index => $id_buku) {
                    $qtyRequired = $request->jumlah[$index];

                    // Kunci baris data buku untuk menghindari race condition stok
                    $buku = Buku::lockForUpdate()->findOrFail($id_buku);
                    
                    // Validasi kecukupan stok fisik di database
                    if ($buku->jumlah < $qtyRequired) {
                        throw new \Exception("Stok buku '{$buku->judul}' tidak mencukupi atau habis! (Sisa stok: {$buku->jumlah})");
                    }

                    // Simpan data ke tabel relasi detail
                    DetailPeminjaman::create([
                        'id_peminjaman' => $peminjaman->id_peminjaman,
                        'id_buku'       => $buku->id_buku,
                        'jumlah'        => $qtyRequired,
                        'sub_total'     => null
                    ]);

                    // Potong stok buku secara otomatis
                    $buku->decrement('jumlah', $qtyRequired);
                }
            });

            return redirect()->route('peminjaman.index')->with('success', 'Transaksi peminjaman multi-buku berhasil dicatat!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage())->withInput();
        }
    }

    // 3. Proses Pengembalian Seluruh Buku + Hitung Denda Otomatis (FIXED LOGIC)
    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::with('details')->findOrFail($id);
        
        if ($peminjaman->status_peminjaman === 'kembali') {
            return redirect()->back()->with('error', 'Buku pada transaksi ini sudah dikembalikan.');
        }

        try {
            DB::transaction(function () use ($peminjaman) {
                // PERBAIKAN: Paksa perbandingan murni tanggal (00:00:00) agar selisih hari valid
                $tanggalKembali = Carbon::today();
                $jatuhTempo     = Carbon::parse($peminjaman->jatuh_tempo)->startOfDay();
                $totalDenda     = 0;

                // Hitung denda jika tanggal kembali melewati batas jatuh tempo
                if ($tanggalKembali->gt($jatuhTempo)) {
                    $selisihHari = $tanggalKembali->diffInDays($jatuhTempo);
                    $totalDenda  = $selisihHari * 1000; // Tarif denda Rp 1.000,- per hari
                }

                // Update status data induk peminjaman
                $peminjaman->update([
                    'tanggal_kembali'   => $tanggalKembali->toDateString(),
                    'status_peminjaman' => 'kembali',
                    'denda'             => $totalDenda
                ]);

                // Mengembalikan semua stok buku yang ada di dalam detail transaksi ini
                foreach ($peminjaman->details as $detail) {
                    $buku = Buku::lockForUpdate()->findOrFail($detail->id_buku);
                    $buku->increment('jumlah', $detail->jumlah); // Stok bertambah kembali
                }
            });

            return redirect()->route('peminjaman.index')->with('success', 'Seluruh buku berhasil dikembalikan dan denda telah diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }

    // 4. Fitur Cetak Bukti Peminjaman Buku Berupa Dokumen PDF (Output Dokumen Sistem)
    public function cetak($id)
    {
        $peminjaman = Peminjaman::with(['user', 'details.buku'])->findOrFail($id);

        $pdf = Pdf::loadView('admin.peminjaman.cetak_pdf', compact('peminjaman'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('Bukti_Peminjaman_TRX_' . $peminjaman->id_peminjaman . '.pdf');
    }

    // ==================== MODUL FILTRASI LAPORAN OPSI 1 ====================
    
    // 5. Menampilkan halaman filter rentang tanggal laporan
    public function halamanLaporan()
    {
        return view('admin.laporan.index');
    }

    // 6. Memproses ekspor rekapitulasi data sirkulasi ke dokumen PDF Landscape
    public function cetakLaporan(Request $request)
    {
        $request->validate([
            'tgl_awal'  => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
        ]);

        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;

        // Tarik data manifes transaksi yang masuk di dalam rentang periode kalender
        $laporans = Peminjaman::with(['user', 'details.buku'])
                    ->whereBetween('tanggal_pinjam', [$tgl_awal, $tgl_akhir])
                    ->oldest()
                    ->get();

        // Akumulasi kalkulasi total kas denda yang terkumpul pada periode tersebut
        $totalDendaPeriode = $laporans->sum('denda');

        $pdf = Pdf::loadView('admin.laporan.cetak_pdf', compact('laporans', 'tgl_awal', 'tgl_akhir', 'totalDendaPeriode'))
                  ->setPaper('a4', 'landscape'); // Format landscape agar kolom data muat lebar ke samping

        return $pdf->stream("Laporan_Sirkulasi_{$tgl_awal}_to_{$tgl_akhir}.pdf");
    }

    // ==================== MODUL PEMBAYARAN KAS DENDA OPSI 2 ====================

    // 7. Mengubah nominal denda menjadi 0 (Tanda Lunas Fisik) setelah menerima uang tunai
    public function bayarDenda($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->denda == 0) {
            return redirect()->back()->with('error', 'Transaksi ini tidak memiliki tunggakan denda.');
        }

        try {
            DB::transaction(function () use ($peminjaman) {
                $peminjaman->update([
                    'denda' => 0
                ]);
            });

            return redirect()->route('peminjaman.index')->with('success', 'Pembayaran denda untuk TRX #' . $peminjaman->id_peminjaman . ' berhasil diterima. Status: LUNAS.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pembayaran denda: ' . $e->getMessage());
        }
    }
}