<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // =========================================================================
    // TERMINAL ADMIN: Kelola Data Master Buku
    // =========================================================================

    // 1. Menampilkan daftar semua buku + data kategori untuk dropdown admin
    public function index()
    {
        $bukus = Buku::with('kategori')->get();
        $kategoris = Kategori::all();
        return view('admin.buku.index', compact('bukus', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.buku.create', compact('kategoris'));
    }

    // 2. Menyimpan data buku baru dengan GENERATE KODE OTOMATIS
    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategoris,id_kategori',
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string',
            'penerbit' => 'required|string',
            'tahun_terbit' => 'required|integer',
            'jumlah' => 'required|integer|min:0',
            'lokasi_rak' => 'required|string',
        ]);

        // Ambil 3 huruf pertama dari nama kategori (Contoh: "Teknologi" -> "TEK")
        $kategori = Kategori::findOrFail($request->id_kategori);
        $tigaHuruf = strtoupper(substr($kategori->nama_kategori, 0, 3));

        // Cari buku terakhir dengan prefix kategori yang sama untuk menentukan nomor urut
        $bukuTerakhir = Buku::where('kode_buku', 'LIKE', $tigaHuruf . '-%')
                            ->orderBy('id_buku', 'DESC')
                            ->first();

        if ($bukuTerakhir) {
            // Ambil angka setelah tanda "-" (contoh: "TEK-00001" ambil "00001")
            $nomorTerakhir = substr($bukuTerakhir->kode_buku, 4);
            $nomorUrutBaru = (int)$nomorTerakhir + 1;
        } else {
            $nomorUrutBaru = 1;
        }

        // Pad nomor urut menjadi 5 digit (contoh: 1 -> "00001")
        $angkaFormat = str_pad($nomorUrutBaru, 5, '0', STR_PAD_LEFT);
        $kodeBukuOtomatis = $tigaHuruf . '-' . $angkaFormat;

        // Satukan kode otomatis ke dalam data input
        $data = $request->all();
        $data['kode_buku'] = $kodeBukuOtomatis;

        Buku::create($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan dengan Kode: ' . $kodeBukuOtomatis);
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        return view('admin.buku.show', compact('buku'));
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::all();
        return view('admin.buku.edit', compact('buku', 'kategoris'));
    }

    // 3. Memperbarui data buku tanpa merusak atau merubah kode buku yang sudah ada
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategoris,id_kategori',
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string',
            'penerbit' => 'required|string',
            'tahun_terbit' => 'required|integer',
            'jumlah' => 'required|integer|min:0',
            'lokasi_rak' => 'required|string',
        ]);

        $buku = Buku::findOrFail($id);
        
        // Ambil data request awal
        $data = $request->all();

        // LOGIKA PERUBAHAN KODE: Jika admin mengganti kategori buku saat edit, kode buku digenerate ulang sesuai kategori baru
        if ($buku->id_kategori != $request->id_kategori) {
            $kategoriBaru = Kategori::findOrFail($request->id_kategori);
            $tigaHuruf = strtoupper(substr($kategoriBaru->nama_kategori, 0, 3));

            $bukuTerakhir = Buku::where('kode_buku', 'LIKE', $tigaHuruf . '-%')
                                ->orderBy('id_buku', 'DESC')
                                ->first();

            if ($bukuTerakhir) {
                $nomorTerakhir = substr($bukuTerakhir->kode_buku, 4);
                $nomorUrutBaru = (int)$nomorTerakhir + 1;
            } else {
                $nomorUrutBaru = 1;
            }

            $angkaFormat = str_pad($nomorUrutBaru, 5, '0', STR_PAD_LEFT);
            $data['kode_buku'] = $tigaHuruf . '-' . $angkaFormat;
        } else {
            // Jika kategori tidak berubah, pertahankan kode buku yang lama
            $data['kode_buku'] = $buku->getOriginal('kode_buku');
        }

        $buku->update($data);

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }

    // =========================================================================
    // TERMINAL USER / MEMBER: Hak Akses Mahasiswa (Read-Only)
    // =========================================================================
    
    // 4. Menampilkan katalog buku khusus mahasiswa + Fitur Live Search & Filter Sidebar
    public function userIndex(Request $request)
    {
        // Tangkap keyword search DAN parameter filter kategori dari URL sidebar
        $search = $request->get('search');
        $filterKategori = $request->get('kategori');

        // Mengambil data buku terikat dengan kategori aslinya + Filter Adaptif
        $books = Buku::with('kategori')
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'LIKE', '%' . $search . '%')
                      ->orWhere('penerbit', 'LIKE', '%' . $search . '%')
                      ->orWhere('pengarang', 'LIKE', '%' . $search . '%')
                      ->orWhere('kode_buku', 'LIKE', '%' . $search . '%');
                });
            })
            ->when($filterKategori, function($query) use ($filterKategori) {
                // FIXED: Menyaring buku berdasarkan ID Kategori yang diklik di sidebar kiri
                $query->where('id_kategori', $filterKategori);
            })
            ->orderBy('judul', 'asc')
            ->paginate(8) // Grid 4x2
            ->withQueryString(); // Mempertahankan parameter URL saat berganti halaman pagination

        return view('user.buku_catalog', compact('books', 'search', 'filterKategori'));
    }

    public function storeBooking($id_buku)
{
    $id_user = auth()->id();

    // 1. Ambil data buku dengan perlindungan lock
    $buku = Buku::findOrFail($id_buku);

    // 2. HITUNG PROTEKSI KUOTA (Maksimal 3 Buku)
    $bukuSedangDipinjam = \App\Models\DetailPeminjaman::whereHas('peminjaman', function($query) use ($id_user) {
        $query->where('id_user', $id_user)
              ->whereIn('status_peminjaman', ['dipinjam', 'booking']); // Booking juga memotong kuota!
    })->sum('jumlah');

    if ($bukuSedangDipinjam >= 3) {
        return redirect()->back()->with('error', 'Booking Gagal! Batas kuota pinjaman Anda sudah habis (Maksimal 3 Buku).');
    }

    // 3. PROTEKSI STOK BUKU FISIK
    if ($buku->jumlah <= 0) {
        return redirect()->back()->with('error', 'Booking Gagal! Stok buku fisik saat ini sedang habis.');
    }

    // 4. ENGINE TRANSACTION DATABASE: Menjamin data masuk utuh
    \DB::beginTransaction();
    try {
        // A. Buat Master Peminjaman dengan status 'booking'
        $peminjaman = \App\Models\Peminjaman::create([
            'id_user' => $id_user,
            'tanggal_pinjam' => \Carbon\Carbon::today()->toDateString(),
            'jatuh_tempo' => \Carbon\Carbon::today()->addDay()->toDateString(), // Batas ambil buku 1 hari (24 Jam)
            'status_peminjaman' => 'booking',
            'denda' => 0
        ]);

        // B. Buat Detail Peminjaman
        \App\Models\DetailPeminjaman::create([
            'id_peminjaman' => $peminjaman->id_peminjaman,
            'id_buku' => $buku->id_buku,
            'jumlah' => 1 // Setiap booking default mengunci 1 buku
        ]);

        // C. Potong stok buku asli di tabel bukus
        $buku->decrement('jumlah', 1);

        \DB::commit();
        return redirect()->route('user.dashboard')->with('success', 'Buku "' . $buku->judul . '" Berhasil dibooking! Silakan ambil di perpus dalam 24 jam.');

    } catch (\Exception $e) {
        \DB::rollback();
        return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses booking.');
    }
}
}