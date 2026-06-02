<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="Login Pelanggan",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="budi@gmail.com"),
     *             @OA\Property(property="password", type="string", example="budi1234")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login Berhasil",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", ref="#/components/schemas/User"),
     *                 @OA\Property(property="token", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Email Atau Password Salah")
     * )
    */
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

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Authentication"},
     *     summary="Logout Pelanggan",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Logout berhasil")
     * )
    */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout Berhasil',
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/profile",
     *     tags={"Authentication"},
     *     summary="Ambil Data Profil Yang Sedang Login",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Data profil berhasil diambil",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     )
     * )
    */
    public function profile(Request $request)
    {
        return response()->json([
            'status' => true,
            'data' => tap($request->user()->load('customer'), function ($user) {
                $user->makeVisible('plain_password');
            }),
        ], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/profile",
     *     tags={"Authentication"},
     *     summary="Update Profil Pelanggan",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Budi Santoso"),
     *             @OA\Property(property="phone", type="string", example="08123456789"),
     *             @OA\Property(property="address", type="string", example="Jl. Contoh No. 1")
     *         )
     *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Profil Berhasil Diperbarui",
    *         @OA\JsonContent(type="object",
    *             @OA\Property(property="status", type="boolean"),
    *             @OA\Property(property="message", type="string"),
    *             @OA\Property(property="data", ref="#/components/schemas/User")
    *         )
    *     )
     * )
    */
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