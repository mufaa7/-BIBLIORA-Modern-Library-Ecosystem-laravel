<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // REQUIRED FOR MANAGED STORAGE OPERATIONS

class PendaftaranController extends Controller
{
    // Display the registration form and members list
    public function index()
    {
        // Fetch all members with 'user' role ordered by latest id_user
        $members = User::where('role', 'user')->orderBy('id_user', 'DESC')->get();
        return view('admin.pendaftaran.index', compact('members'));
    }

    // Process new member registration + profile picture validation
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'no_telp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Optional image, max 2MB
        ]);

        $photoPath = null;

        // If file exists, store it securely inside public/avatars
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
            'foto_profil' => $photoPath,
        ]);

        // English success message synced with Blade Alert
        return redirect()->route('pendaftaran.index')->with('success', 'New library member has been successfully registered!');
    }

    // Process dynamic photo upload from table row without ruining dashboard state
    public function updateAvatarByAdmin(Request $request)
    {
        $request->validate([
            'id_user' => 'required',
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Must be a valid image
        ]);

        // FIXED BLIND SPOT: Explicitly search by your custom primary key 'id_user' to prevent crashes
        $user = User::where('id_user', $request->id_user)->firstOrFail();

        // Anti-garbage file system: Delete old photo from storage disk if exists
        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        // Store new file to public/storage/avatars
        $path = $request->file('foto_profil')->store('avatars', 'public');

        // Update model column instance
        $user->foto_profil = $path;
        $user->save();

        // English success message synced with Blade Alert
        return redirect()->route('pendaftaran.index')->with('success', 'Member profile picture has been successfully updated!');
    }

    // Render physical library card view with custom barcodes/QR codes
    public function cetakKartu($id)
    {
        // Fetch specific user data using blueprint primary key id_user
        $member = User::where('id_user', $id)->firstOrFail();
        
        return view('admin.pendaftaran.cetak_card', compact('member'));
    }
}