<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // 1. Menampilkan daftar semua buku + data kategori untuk dropdown
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
}