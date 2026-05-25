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
    // 1. Menampilkan semua riwayat transaksi peminjaman
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'details.buku'])->latest()->get();
        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    // 2. PROSES MENCATAT PEMINJAMAN LANGSUNG VIA ADMIN (OFFLINE)
    public function store(Request $request)
    {
        $request->validate([
            'id_user'   => 'required|exists:users,id_user',
            'id_buku'   => 'required|array|min:1|max:3', 
            'id_buku.*' => 'required|exists:bukus,id_buku', 
            'jumlah'    => 'required|array',
            'jumlah.*'  => 'required|integer|min:1',
            'durasi'    => 'required|integer|in:1,3,7',
        ]);

        if (count($request->id_buku) !== count(array_unique($request->id_buku))) {
            return redirect()->back()->with('error', 'Gagal! Ada judul buku yang duplikat dalam form input.')->withInput();
        }

        // PROTEKSI 1: AUTOMATIC BLACKLIST CHECK
        $punyaTunggakanOverdue = Peminjaman::where('id_user', $request->id_user)
            ->where('status_peminjaman', 'dipinjam')
            ->where('jatuh_tempo', '<', Carbon::today()->toDateString())
            ->exists();

        if ($punyaTunggakanOverdue) {
            return redirect()->back()
                ->with('error', '⚠️ TRANSAKSI DITOLAK! Anggota ini ditangguhkan (Blacklist) karena memiliki pinjaman buku yang telah melewati jatuh tempo. Selesaikan tunggakan terlebih dahulu!')
                ->withInput();
        }

        // PROTEKSI 2: VALIDASI KUOTA MAKSIMAL 3 BUKU (Termasuk yang berstatus booking)
        $bukuSedangDipinjam = DetailPeminjaman::whereHas('peminjaman', function($query) use ($request) {
            $query->where('id_user', $request->id_user)
                  ->whereIn('status_peminjaman', ['dipinjam', 'booking']);
        })->sum('jumlah');

        $bukuBaruAkanDipinjam = array_sum($request->jumlah);

        if (($bukuSedangDipinjam + $bukuBaruAkanDipinjam) > 3) {
            $sisaKuota = 3 - $bukuSedangDipinjam;
            return redirect()->back()->with('error', "Transaksi Ditolak! Anggota ini sudah mengantongi {$bukuSedangDipinjam} buku di sistem. Sisa batas kuota pinjam saat ini: {$sisaKuota} buku.") ->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $tanggalPinjam = Carbon::now()->toDateString();
                $jatuhTempo = Carbon::now()->addDays($request->durasi)->toDateString();

                $peminjaman = Peminjaman::create([
                    'id_user'           => $request->id_user,
                    'tanggal_pinjam'    => $tanggalPinjam,
                    'jatuh_tempo'       => $jatuhTempo,
                    'status_peminjaman' => 'dipinjam',
                    'denda'             => 0
                ]);

                foreach ($request->id_buku as $index => $id_buku) {
                    $qtyRequired = $request->jumlah[$index];
                    $buku = Buku::lockForUpdate()->findOrFail($id_buku);
                    
                    if ($buku->jumlah < $qtyRequired) {
                        throw new \Exception("Stok buku '{$buku->judul}' tidak mencukupi atau habis! (Sisa stok: {$buku->jumlah})");
                    }

                    DetailPeminjaman::create([
                        'id_peminjaman' => $peminjaman->id_peminjaman,
                        'id_buku'       => $buku->id_buku,
                        'jumlah'        => $qtyRequired,
                        'sub_total'     => null
                    ]);

                    $buku->decrement('jumlah', $qtyRequired);
                }
            });

            return redirect()->route('peminjaman.index')->with('success', 'Transaksi peminjaman multi-buku berhasil dicatat!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage())->withInput();
        }
    }

    // =========================================================================
    // INTERACTION ENGINE: Validasi Pengubahan Status Pre-Order Booking Menjadi Pinjam (FIXED DINAMIS)
    // =========================================================================
    public function konfirmasiBooking(Request $request, $id)
    {
        // 1. Validasi input durasi yang dikirimkan oleh admin via dropdown baris tabel
        $request->validate([
            'durasi' => 'required|integer|in:1,3,7'
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status_peminjaman !== 'booking') {
            return redirect()->back()->with('error', 'Transaksi ini bukan berstatus booking.');
        }

        try {
            DB::transaction(function () use ($peminjaman, $request) {
                // 2. HITUNG VALUASI TANGGAL SECARA DINAMIS SESUAI REQUEST INPUT ADMIN
                $tanggalPinjamHariIni = Carbon::today()->toDateString();
                $jatuhTempoDinamis    = Carbon::today()->addDays($request->durasi)->toDateString();

                // Ubah status dari 'booking' ke 'dipinjam' dan tiban timeline penanggalannya
                $peminjaman->update([
                    'tanggal_pinjam'    => $tanggalPinjamHariIni,
                    'jatuh_tempo'       => $jatuhTempoDinamis,
                    'status_peminjaman' => 'dipinjam'
                ]);
            });

            return redirect()->route('peminjaman.index')->with('success', 'Booking berhasil divalidasi! Masa pinjam ' . $request->durasi . ' hari resmi berjalan.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses konfirmasi booking: ' . $e->getMessage());
        }
    }

    // 3. Proses Pengembalian Seluruh Buku + Hitung Denda Otomatis
    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::with('details')->findOrFail($id);
        
        if ($peminjaman->status_peminjaman === 'kembali') {
            return redirect()->back()->with('error', 'Buku pada transaksi ini sudah dikembalikan.');
        }

        try {
            DB::transaction(function () use ($peminjaman) {
                $tanggalKembali = Carbon::today();
                $jatuhTempo     = Carbon::parse($peminjaman->jatuh_tempo)->startOfDay();
                $totalDenda     = 0;

                $totalBukuDipinjam = $peminjaman->details->sum('jumlah');

                if ($tanggalKembali->gt($jatuhTempo)) {
                    $selisihHari = $tanggalKembali->diffInDays($jatuhTempo);
                    $totalDenda  = $selisihHari * 1000 * $totalBukuDipinjam; 
                }

                $peminjaman->update([
                    'tanggal_kembali'   => $tanggalKembali->toDateString(),
                    'status_peminjaman' => 'kembali',
                    'denda'             => $totalDenda
                ]);

                foreach ($peminjaman->details as $detail) {
                    $buku = Buku::lockForUpdate()->findOrFail($detail->id_buku);
                    $buku->increment('jumlah', $detail->jumlah); 
                }
            });

            return redirect()->route('peminjaman.index')->with('success', 'Seluruh buku berhasil dikembalikan dan denda telah diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }

    // 4. Fitur Cetak Bukti Peminjaman Buku Berupa Dokumen PDF
    public function cetak($id)
    {
        $peminjaman = Peminjaman::with(['user', 'details.buku'])->findOrFail($id);

        $pdf = Pdf::loadView('admin.peminjaman.cetak_pdf', compact('peminjaman'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('Bukti_Peminjaman_TRX_' . $peminjaman->id_peminjaman . '.pdf');
    }

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

        $laporans = Peminjaman::with(['user', 'details.buku'])
                    ->whereBetween('tanggal_pinjam', [$tgl_awal, $tgl_akhir])
                    ->oldest()
                    ->get();

        $totalDendaPeriode = $laporans->sum('denda');

        $pdf = Pdf::loadView('admin.laporan.cetak_pdf', compact('laporans', 'tgl_awal', 'tgl_akhir', 'totalDendaPeriode'))
                  ->setPaper('a4', 'landscape'); 

        return $pdf->stream("Laporan_Sirkulasi_{$tgl_awal}_to_{$tgl_akhir}.pdf");
    }

    // 7. Melunasi denda tanpa menghapus riwayat kas keuangan
    public function bayarDenda($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->denda == 0) {
            return redirect()->back()->with('error', 'Transaksi ini tidak memiliki tunggakan denda.');
        }

        try {
            DB::transaction(function () use ($peminjaman) {
                $peminjaman->update([
                    'status_peminjaman' => 'lunas'
                ]);
            });

            return redirect()->route('peminjaman.index')->with('success', 'Pembayaran denda untuk TRX #' . $peminjaman->id_peminjaman . ' berhasil diterima. Status: LUNAS.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pembayaran denda: ' . $e->getMessage());
        }
    }
}