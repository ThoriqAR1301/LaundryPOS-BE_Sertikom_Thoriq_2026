<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Transaction;
use App\Models\Service;
use App\Models\Customer;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['admin', 'customer.user', 'service'])->get();

        return response()->json([
            'status' => true,
            'data' => $transactions,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'weight' => 'required|numeric|min:0.1',
            'payment_method' => 'required|in:cash,transfer',
        ], [
            'customer_id.required' => 'Customer Wajib Dipilih',
            'customer_id.exists' => 'Customer Tidak Ditemukan',
            'service_id.required' => 'Layanan Wajib Dipilih',
            'service_id.exists' => 'Layanan Tidak Ditemukan',
            'weight.required' => 'Berat Wajib Diisi',
            'weight.numeric' => 'Berat Harus Berupa Angka',
            'weight.min' => 'Berat Minimal 0.1',
            'payment_method.required' => 'Metode Pembayaran Wajib Dipilih',
            'payment_method.in' => 'Metode Pembayaran Harus Cash Atau Transfer',
        ]);

        $service = Service::find($request->service_id);
        $total_price = $service->price * $request->weight;

        $lastTransaction = Transaction::latest()->first();
        $lastNumber = $lastTransaction ? (int) substr($lastTransaction->invoice_code, 4) : 0;
        $invoiceCode = 'LND-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        $transaction = Transaction::create([
            'invoice_code' => $invoiceCode,
            'admin_id' => $request->user()->id,
            'customer_id' => $request->customer_id,
            'service_id' => $request->service_id,
            'total_price' => $total_price,
            'status' => 'antrian',
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Transaksi Berhasil Dibuat',
            'data' => $transaction->load(['admin', 'customer.user', 'service']),
        ], 201);
    }

    public function show($id)
    {
        $transaction = Transaction::with(['admin', 'customer.user', 'service'])->find($id);

        if (! $transaction) {
            return response()->json([
                'status' => false,
                'message' => 'Transaksi Tidak Ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $transaction,
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if (! $transaction) {
            return response()->json([
                'status' => false,
                'message' => 'Transaksi Tidak Ditemukan',
            ], 404);
        }

        $request->validate([
            'status' => 'required|in:antrian,dicuci,disetrika,siap diambil,diambil',
        ], [
            'status.required' => 'Status Wajib Diisi',
            'status.in' => 'Status Tidak Valid',
        ]);

        $transaction->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Status Transaksi Berhasil Diperbarui',
            'data' => $transaction->load(['admin', 'customer.user', 'service']),
        ], 200);
    }

    public function uploadPaymentProof(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if (! $transaction) {
            return response()->json([
                'status' => false,
                'message' => 'Transaksi Tidak Ditemukan',
            ], 404);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'payment_proof.required' => 'Bukti Pembayaran Wajib Diupload',
            'payment_proof.image' => 'File Harus Berupa Gambar',
            'payment_proof.mimes' => 'Format Gambar Harus jpg, jpeg, Atau png',
            'payment_proof.max' => 'Ukuran Gambar Maksimal 2MB',
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

        return response()->json([
            'status' => true,
            'message' => 'Bukti Pembayaran Berhasil Diupload',
            'data' => $transaction->load(['admin', 'customer.user', 'service']),
        ], 200);
    }

    public function statusLaundry(Request $request)
    {
        $user = $request->user();
        $customer = $user->customer;

        if (! $customer) {
            return response()->json([
                'status' => false,
                'message' => 'Data Customer Tidak Ditemukan',
            ], 404);
        }

        $transactions = Transaction::with(['service'])->where('customer_id', $customer->id)->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $transactions,
        ], 200);
    }
}