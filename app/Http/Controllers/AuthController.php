<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'fullname' => 'required'
        ]);

        $userId = DB::table('users')->insertGetId([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'fullname' => $request->fullname,
            'role' => 'pasien',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = DB::table('users')->where('id', $userId)->first();
        
        // Generate token manual
        $token = base64_encode(Str::random(40) . time());

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token
        ]);
    }

    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }

        // Generate token manual
        $token = base64_encode(Str::random(40) . time());

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token
        ]);
    }

    // GET PROFILE (ME)
    public function me(Request $request)
    {
        // Ambil user_id dari request (kirim dari Flutter)
        $userId = $request->user_id;
        
        // Atau dari header? lebih simple: terima dari parameter
        if (!$userId) {
            // Coba ambil dari query string
            $userId = $request->query('user_id');
        }
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'user_id diperlukan. Kirim parameter user_id'
            ], 400);
        }
        
        $user = DB::table('users')->where('id', $userId)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'fullname' => $user->fullname,
            'role' => $user->role
        ]);
    }

    // UPDATE PROFILE
    public function update(Request $request)
    {
        $userId = $request->user_id;
        $fullname = $request->fullname;
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'user_id diperlukan'
            ], 400);
        }
        
        $user = DB::table('users')->where('id', $userId)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }
        
        // Update nama
        DB::table('users')->where('id', $userId)->update([
            'fullname' => $fullname,
            'updated_at' => now()
        ]);
        
        // Update password jika ada
        if ($request->new_password) {
            DB::table('users')->where('id', $userId)->update([
                'password' => Hash::make($request->new_password)
            ]);
        }
        
        $updatedUser = DB::table('users')->where('id', $userId)->first();
        
        return response()->json([
            'success' => true,
            'user' => $updatedUser
        ]);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        // Untuk token manual, cukup return sukses
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}