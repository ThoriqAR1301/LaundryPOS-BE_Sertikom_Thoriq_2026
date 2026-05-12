<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('user')->get();

        return response()->json([
            'status' => true,
            'data'   => $customers,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone'    => 'required|string|max:15',
            'address'  => 'required|string',
        ], [
            'name.required'     => 'Nama Wajib Diisi',
            'email.required'    => 'Email Wajib Diisi',
            'email.email'       => 'Format Email Tidak Valid',
            'email.unique'      => 'Email Sudah Digunakan',
            'password.required' => 'Password Wajib Diisi',
            'password.min'      => 'Password Minimal 6 Karakter',
            'phone.required'    => 'Nomor HP Wajib Diisi',
            'phone.max'         => 'Nomor HP Maksimal 15 Karakter',
            'address.required'  => 'Alamat Wajib Diisi',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'customer',
        ]);

        $customer = Customer::create([
            'user_id' => $user->id,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Pelanggan Berhasil Ditambahkan',
            'data'    => $customer->load('user'),
        ], 201);
    }

    public function show($id)
    {
        $customer = Customer::with('user')->find($id);

        if (! $customer) {
            return response()->json([
                'status'  => false,
                'message' => 'Pelanggan Tidak Ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $customer,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::with('user')->find($id);

        if (! $customer) {
            return response()->json([
                'status'  => false,
                'message' => 'Pelanggan Tidak Ditemukan',
            ], 404);
        }

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $customer->user_id,
            'phone'   => 'required|string|max:15',
            'address' => 'required|string',
        ], [
            'name.required'    => 'Nama Wajib Diisi',
            'email.required'   => 'Email Wajib Diisi',
            'email.email'      => 'Format Email Tidak Valid',
            'email.unique'     => 'Email Sudah Digunakan',
            'phone.required'   => 'Nomor HP Wajib Diisi',
            'phone.max'        => 'Nomor HP Maksimal 15 Karakter',
            'address.required' => 'Alamat Wajib Diisi',
        ]);

        $customer->user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        $customer->update([
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Pelanggan Berhasil Diperbarui',
            'data'    => $customer->load('user'),
        ], 200);
    }

    public function destroy($id)
    {
        $customer = Customer::with('user')->find($id);

        if (! $customer) {
            return response()->json([
                'status'  => false,
                'message' => 'Pelanggan Tidak Ditemukan',
            ], 404);
        }

        $customer->user->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Pelanggan Berhasil Dihapus',
        ], 200);
    }
}