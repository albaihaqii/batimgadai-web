<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        if ($user->status !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak aktif.',
            ], 403);
        }

        // Hapus token lama
        $user->tokens()->delete();

        // Buat token baru
        $token = $user->createToken('api-token-' . $user->role)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data'    => [
                'user'  => [
                    'id'         => $user->id,
                    'nama'       => $user->nama,
                    'email'      => $user->email,
                    'role'       => $user->role,
                    'cabang_id'  => $user->cabang_id,
                    'cabang'     => $user->branch?->nama,
                    'foto'       => $user->foto ? asset('storage/' . $user->foto) : null,
                    'status'     => $user->status,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'        => $user->id,
                'nama'      => $user->nama,
                'email'     => $user->email,
                'role'      => $user->role,
                'cabang_id' => $user->cabang_id,
                'cabang'    => $user->branch?->nama,
                'foto'      => $user->foto ? asset('storage/' . $user->foto) : null,
                'status'    => $user->status,
            ],
        ]);
    }
}