<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Models\User;

class CustomerWebController extends Controller
{
    public function index()
    {
        $customers = Customer::with('user')->latest()->get();
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        Customer::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'Customer Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        $customer = Customer::with('user')->findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::with('user')->findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->user_id,
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
        ]);

        $customer->user->update(['name' => $request->name, 'email' => $request->email]);
        $customer->update(['phone' => $request->phone, 'address' => $request->address]);

        return redirect()->route('admin.customers.index')->with('success', 'Customer Berhasil Diperbarui');
    }

    public function destroy($id)
    {
        $customer = Customer::with('user')->findOrFail($id);
        $customer->user->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Customer Berhasil Dihapus');
    }
}