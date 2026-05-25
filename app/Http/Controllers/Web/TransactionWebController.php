<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Service;

class TransactionWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['customer.user', 'service']);

        if ($request->status) $query->where('status', $request->status);
        if ($request->payment_status) $query->where('payment_status', $request->payment_status);

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) { $q->whereHas('customer.user', fn($q) => $q->where('name', 'like', "%$search%"))->orWhere('invoice_code', 'like', "%$search%"); });
        }

        $transactions = $query->latest()->paginate(10);

        $summary = [
            'antrian' => Transaction::withTrashed()->where('status', 'antrian')->count(),
            'dicuci' => Transaction::withTrashed()->where('status', 'dicuci')->count(),
            'disetrika' => Transaction::withTrashed()->where('status', 'disetrika')->count(),
            'siap diambil' => Transaction::withTrashed()->where('status', 'siap diambil')->count(),
            'diambil' => Transaction::withTrashed()->where('status', 'diambil')->count(),
            'total' => Transaction::withTrashed()->count(),
            'lunas' => Transaction::withTrashed()->where('payment_status', 'paid')->count(),
            'pending' => Transaction::withTrashed()->where('payment_status', 'pending')->count(),
        ];

        return view('admin.transactions.index', compact('transactions', 'summary'));
    }

    public function create()
    {
        $customers = Customer::with('user')->get();
        $services = Service::all();
        return view('admin.transactions.create', compact('customers', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'weight' => 'required|numeric|min:0.1',
            'payment_method' => 'required|in:cash,transfer',
            'cloth_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'cloth_photo.image' => 'Foto Kondisi Baju Harus Berupa Gambar',
            'cloth_photo.mimes' => 'Format Foto Harus jpg, jpeg, Atau png',
            'cloth_photo.max' => 'Ukuran Foto Maksimal 2MB',
        ]);

        $service = Service::findOrFail($request->service_id);
        $total_price = $service->price * $request->weight;

        $lastTransaction = Transaction::withTrashed()->latest('id')->first();
        $lastNumber = $lastTransaction ? (int) substr($lastTransaction->invoice_code, 4) : 0;
        $invoiceCode = 'LND-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        $clothPhotoPath = null;
        if ($request->hasFile('cloth_photo')) {
            $clothPhotoPath = $request->file('cloth_photo')->store('cloth_photos', 'public');
        }

        Transaction::create([
            'invoice_code' => $invoiceCode,
            'admin_id' => auth()->id(),
            'customer_id' => $request->customer_id,
            'service_id' => $request->service_id,
            'service_unit' => $request->weight,
            'total_price' => $total_price,
            'status' => 'antrian',
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'cloth_photo' => $clothPhotoPath,
        ]);

        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi Berhasil Dibuat');
    }

    public function show($id)
    {
        $transaction = Transaction::with(['admin', 'customer.user', 'service'])->findOrFail($id);

        return view('admin.transactions.show', compact('transaction'));
    }

    public function edit($id)
    {
        $transaction = Transaction::with(['customer.user', 'service'])->findOrFail($id);
        $customers = Customer::with('user')->get();
        $services = Service::all();
        return view('admin.transactions.edit', compact('transaction', 'customers', 'services'));
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'service_unit' => 'required|numeric|min:0.1',
            'payment_method' => 'required|in:cash,transfer',
        ]);

        $service = Service::findOrFail($request->service_id);
        $total_price = $service->price * $request->service_unit;

        $transaction->update([
            'customer_id' => $request->customer_id,
            'service_id' => $request->service_id,
            'service_unit' => $request->service_unit,
            'total_price' => $total_price,
            'payment_method' => $request->payment_method,
        ]);

        return redirect()->route('admin.transactions.show', ['id' => $transaction->id])->with('success', 'Transaksi Berhasil Diperbarui');
    }

    public function updateStatus(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $request->validate([
            'status' => 'required|in:antrian,dicuci,disetrika,siap diambil,diambil',
        ]);

        $updateData = ['status' => $request->status];

        if ($request->status === 'diambil' && $transaction->payment_method === 'cash') {
            $updateData['payment_status'] = 'paid';
            $updateData['paid_at'] = now();
        }

        $transaction->update($updateData);

        return back()->with('success', 'Status Cucian Berhasil Diperbarui');
    }

    public function uploadPaymentProof(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($transaction->payment_proof) {
            Storage::disk('public')->delete($transaction->payment_proof);
        }

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        $transaction->update([
            'payment_proof' => $path,
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Bukti Pembayaran Berhasil Diupload');
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        if (!$transaction->isDeletable()) {
            return back()->with('error', 'Transaksi Belum Memenuhi Syarat Untuk Dihapus');
        }

        $transaction->delete();

        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi Berhasil Dihapus Dari Daftar');
    }

    public function printStruk($id)
    {
        $transaction = Transaction::with(['admin', 'customer.user', 'service'])->findOrFail($id);
        return view('admin.transactions.print', compact('transaction'));
    }
}