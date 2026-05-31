<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email Wajib Diisi',
            'email.email' => 'Format Email Tidak Valid',
            'password.required' => 'Password Wajib Diisi',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email Atau Password Salah'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login Berhasil',
            'data' => [
                'user' => tap($user->load('customer'), function ($u) {
                    $u->makeVisible('plain_password');
                }),
                'token' => $token,
            ],
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout Berhasil',
        ], 200);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'status' => true,
            'data' => tap($request->user()->load('customer'), function ($user) {
                $user->makeVisible('plain_password');
            }),
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
        ]);

        $user = $request->user();
        $user->update(['name' => $request->name]);

        $user->customer()->updateOrCreate(
            ['user_id' => $user->id],
            ['phone' => $request->phone, 'address' => $request->address]
        );

        return response()->json([
            'status' => true,
            'message' => 'Profil Berhasil Diperbarui',
            'data' => $user->load('customer'),
        ]);
    }
}