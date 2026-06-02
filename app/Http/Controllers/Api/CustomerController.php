<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('user')->get();


    /**
     * @OA\Post(
     *     path="/api/customers",
     *     tags={"Customer"},
     *     summary="Buat pelanggan",
     *     @OA\RequestBody(@OA\JsonContent(
     *         required={"name","email","password","phone","address"},
     *         @OA\Property(property="name", type="string"),
     *         @OA\Property(property="email", type="string"),
     *         @OA\Property(property="password", type="string"),
     *         @OA\Property(property="phone", type="string"),
     *         @OA\Property(property="address", type="string")
     *     )),
    *     @OA.Response(
    *         response=201,
    *         description="Pelanggan berhasil ditambahkan",
    *         @OA.JsonContent(type="object",
    *             @OA.Property(property="status", type="boolean"),
    *             @OA.Property(property="message", type="string", example="Pelanggan Berhasil Ditambahkan"),
    *             @OA.Property(property="data", ref="#/components/schemas/Customer"),
        *             @OA\Property(property="data", ref="#/components/schemas/Customer"),
    *         )
    *     )
     * )
     */
        return response()->json([
            'status' => true,
            'data' => $customers,
        ], 200);
    }


    /**
     * @OA\Get(
     *     path="/api/customers/{id}",
     *     tags={"Customer"},
     *     summary="Detail pelanggan",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
    *     @OA\Response(
    *         response=200,
    *         description="Detail pelanggan",
    *         @OA\JsonContent(type="object",
    *             @OA\Property(property="status", type="boolean"),
    *             @OA\Property(property="data", ref="#/components/schemas/Customer")
    *         )
    *     ),
    *     @OA\Response(response=404, description="Pelanggan tidak ditemukan")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
        ], [
            'name.required' => 'Nama Wajib Diisi',
            'email.required' => 'Email Wajib Diisi',
            'email.email' => 'Format Email Tidak Valid',
            'email.unique' => 'Email Sudah Digunakan',
            'password.required' => 'Password Wajib Diisi',
            'password.min' => 'Password Minimal 6 Karakter',
            'phone.required' => 'Nomor HP Wajib Diisi',
            'phone.max' => 'Nomor HP Maksimal 15 Karakter',
            'address.required' => 'Alamat Wajib Diisi',
        ]);


    /**
     * @OA\Put(
     *     path="/api/customers/{id}",
     *     tags={"Customer"},
     *     summary="Update pelanggan",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(@OA\JsonContent(
     *         required={"name","email","phone","address"},
     *         @OA\Property(property="name", type="string"),
     *         @OA\Property(property="email", type="string"),
     *         @OA\Property(property="phone", type="string"),
     *         @OA\Property(property="address", type="string")
     *     )),
     *     @OA\Response(response=200, description="Pelanggan berhasil diperbarui"),
     *     @OA\Response(response=404, description="Pelanggan tidak ditemukan")
     * )
     */
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'plain_password' => $request->password,
            'role' => 'customer',
        ]);


    /**
     * @OA\Delete(
     *     path="/api/customers/{id}",
     *     tags={"Customer"},
     *     summary="Hapus pelanggan",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Pelanggan berhasil dihapus"),
     *     @OA\Response(response=404, description="Pelanggan tidak ditemukan")
     * )
     */
        $customer = Customer::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Pelanggan Berhasil Ditambahkan',
            'data' => $customer->load('user'),
        ], 201);
    }

    public function show($id)
    {
        $customer = Customer::with('user')->find($id);

        if (! $customer) {
            return response()->json([
                'status' => false,
                'message' => 'Pelanggan Tidak Ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $customer,
        ], 200);
    }

    public function update(Request $request, $id)
    {
    /**
     * @OA\Put(
     *     path="/api/customers/{id}",
     *     tags={"Customer"},
     *     summary="Update pelanggan",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(@OA\JsonContent(
     *         @OA\Property(property="name", type="string"),
     *         @OA\Property(property="email", type="string"),
     *         @OA\Property(property="phone", type="string"),
     *         @OA\Property(property="address", type="string")
     *     )),
     *     @OA\Response(response=200, description="Pelanggan Berhasil Diperbarui", @OA\JsonContent(type="object", @OA\Property(property="status", type="boolean"), @OA\Property(property="data", ref="#/components/schemas/Customer")))
     * )
     */
        $customer = Customer::with('user')->find($id);

        if (! $customer) {
            return response()->json([
                'status' => false,
                'message' => 'Pelanggan Tidak Ditemukan',
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->user_id,
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
        ], [
            'name.required' => 'Nama Wajib Diisi',
            'email.required' => 'Email Wajib Diisi',
            'email.email' => 'Format Email Tidak Valid',
            'email.unique' => 'Email Sudah Digunakan',
            'phone.required' => 'Nomor HP Wajib Diisi',
            'phone.max' => 'Nomor HP Maksimal 15 Karakter',
            'address.required' => 'Alamat Wajib Diisi',
        ]);

        $customer->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $customer->update([
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Pelanggan Berhasil Diperbarui',
            'data' => $customer->load('user'),
        ], 200);
    }

    public function destroy($id)
    {
    /**
     * @OA\Delete(
     *     path="/api/customers/{id}",
     *     tags={"Customer"},
     *     summary="Hapus pelanggan",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Pelanggan berhasil dihapus", @OA\JsonContent(type="object", @OA\Property(property="status", type="boolean")))
     * )
     */
        $customer = Customer::with('user')->find($id);

        if (! $customer) {
            return response()->json([
                'status' => false,
                'message' => 'Pelanggan Tidak Ditemukan',
            ], 404);
        }

        $customer->user->delete();

        return response()->json([
            'status' => true,
            'message' => 'Pelanggan Berhasil Dihapus',
        ], 200);
    }
}