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
            $query->whereHas('customer.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhere('invoice_code', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->latest()->paginate(10);

        $summary = [
            'antrian' => Transaction::where('status', 'antrian')->count(),
            'dicuci' => Transaction::where('status', 'dicuci')->count(),
            'disetrika' => Transaction::where('status', 'disetrika')->count(),
            'siap diambil' => Transaction::where('status', 'siap diambil')->count(),
            'diambil' => Transaction::where('status', 'diambil')->count(),
            'total' => Transaction::count(),
            'lunas'        => Transaction::where('payment_status', 'paid')->count(),
            'pending'      => Transaction::where('payment_status', 'pending')->count(),
        ];

        return view('admin.transactions.index', compact('transactions', 'summary'));
    }

    public function create()
    {
        $customers = Customer::with('user')->get();
        $services  = Service::all();
        return view('admin.transactions.create', compact('customers', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'weight' => 'required|numeric|min:0.1',
            'payment_method' => 'required|in:cash,transfer',
        ]);

        $service = Service::find($request->service_id);
        $total_price = $service->price * $request->weight;

        $lastTransaction = Transaction::latest()->first();
        $lastNumber = $lastTransaction ? (int) substr($lastTransaction->invoice_code, 4) : 0;
        $invoiceCode = 'LND-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        Transaction::create([
            'invoice_code' => $invoiceCode,
            'admin_id' => auth()->id(),
            'customer_id' => $request->customer_id,
            'service_id' => $request->service_id,
            'total_price' => $total_price,
            'status' => 'antrian',
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
        ]);

        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi Berhasil Dibuat');
    }

    public function show($id)
    {
        $transaction = Transaction::with(['admin', 'customer.user', 'service'])->findOrFail($id);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function updateStatus(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $request->validate(['status' => 'required|in:antrian,dicuci,disetrika,siap diambil,diambil']);
        $transaction->update(['status' => $request->status]);
        return back()->with('success', 'Status Berhasil Diperbarui');
    }

    public function uploadPaymentProof(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $request->validate(['payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048']);

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
}