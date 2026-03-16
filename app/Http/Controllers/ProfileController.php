<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.index', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'nama'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'foto'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->nama  = $request->nama;
        $user->email = $request->email;

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $user->foto = $request->file('foto')->store('foto-profil', 'public');
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function showPassword()
    {
        return view('profile.password', [
            'user' => Auth::user()
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:8|confirmed',
        ], [
            'password_baru.confirmed' => 'Konfirmasi password tidak cocok.',
            'password_baru.min'       => 'Password baru minimal 8 karakter.',
        ]);

        if (!Hash::check($request->password_lama, Auth::user()->getAuthPassword())) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
        }

        $user->password = Hash::make($request->password_baru);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}