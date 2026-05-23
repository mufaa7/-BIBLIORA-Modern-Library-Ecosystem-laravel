<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // WAJIB DIIMPORT UNTUK URUSAN MANAGED STORAGE

class PendaftaranController extends Controller
{
    // Menampilkan halaman form pendaftaran
    public function index()
    {
        // Ambil daftar member yang sudah mendaftar untuk ditampilkan di tabel rekap
        $members = User::where('role', 'user')->orderBy('id_user', 'DESC')->get();
        return view('admin.pendaftaran.index', compact('members'));
    }

    // Memproses simpan data member baru + Validasi Unggah Foto Profil
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'no_telp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Tambahkan validasi foto opsional (Max 2MB)
        ]);

        // Inisialisasi variabel path foto sebagai null bawaan
        $photoPath = null;

        // Jika admin menyertakan berkas foto saat pendaftaran, eksekusi penyimpanan
        if ($request->hasFile('foto_profil')) {
            $photoPath = $request->file('foto_profil')->store('avatars', 'public');
        }

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'role' => 'user',          
            'status_aktif' => 1, 
            'foto_profil' => $photoPath, // Simpan path gambar ke kolom database lu
        ]);

        return redirect()->route('pendaftaran.index')->with('success', 'Anggota baru berhasil didaftarkan!');
    }

    // ==================== INJEKSI FITUR ENTERPRISE: UPDATE FOTO VIA TABEL RESMI ====================
    // Memproses upload foto langsung dari baris tabel member tanpa merusak UI dashboard
    public function updateAvatarByAdmin(Request $request)
    {
        $request->validate([
            'id_user' => 'required',
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Wajib berupa berkas gambar valid
        ]);

        // Cari data member berdasarkan ID yang dilempar JavaScript dari baris tabel
        $user = User::findOrFail($request->id_user);

        // Antisipasi file sampah: Jika member sudah punya foto lama, bersihkan dari piringan storage server
        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        // Simpan file baru ke folder public/storage/avatars
        $path = $request->file('foto_profil')->store('avatars', 'public');

        // Update data kolom model instance
        $user->foto_profil = $path;
        $user->save();

        return redirect()->route('pendaftaran.index')->with('success', 'Foto profil anggota berhasil diperbarui!');
    }

    // ==================== INJEKSI FITUR ENTERPRISE: CETAK KARTU ANGGOTA ====================
    // Menampilkan halaman render kartu perpustakaan fisik beserta barcode dinamis
    public function cetakKartu($id)
    {
        // Ambil data user secara spesifik berdasarkan blueprint primary key id_user lu
        $member = User::where('id_user', $id)->firstOrFail();
        
        return view('admin.pendaftaran.cetak_card', compact('member'));
    }
}