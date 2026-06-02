<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;
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

    /**
     * @OA\Get(
     *     path="/api/transactions",
     *     tags={"Transaksi"},
     *     summary="Daftar Transaksi",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar transaksi berhasil diambil",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Transaction"))
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        /**
         * @OA\Post(
         *     path="/api/transactions",
         *     tags={"Transaksi"},
         *     summary="Buat transaksi",
         *     security={{"bearerAuth":{}}},
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             required={"customer_id","service_id","weight","payment_method"},
         *             @OA\Property(property="customer_id", type="integer"),
         *             @OA\Property(property="service_id", type="integer"),
         *             @OA\Property(property="weight", type="number"),
         *             @OA\Property(property="payment_method", type="string")
         *         )
         *     ),
         *     @OA\Response(
         *         response=201,
         *         description="Transaksi Berhasil Dibuat",
         *         @OA\JsonContent(type="object", @OA\Property(property="status", type="boolean"), @OA\Property(property="data", ref="#/components/schemas/Transaction"))
         *     ),
         *     @OA\Response(response=422, description="Validation Error")
         * )
         */
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


    /**
     * @OA\Get(
     *     path="/api/transactions/{id}",
     *     tags={"Transaksi"},
     *     summary="Detail transaksi",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
        *     @OA\Response(
        *         response=200,
        *         description="Detail transaksi berhasil diambil",
        *         @OA\JsonContent(type="object",
        *             @OA\Property(property="status", type="boolean"),
        *             @OA\Property(property="data", ref="#/components/schemas/Transaction")
        *         )
        *     ),
        *     @OA\Response(response=404, description="Transaksi tidak ditemukan")
     * )
     */
        $service = Service::find($request->service_id);
        $total_price = $service->price * $request->weight;


    /**
     * @OA\Put(
     *     path="/api/transactions/{id}/status",
     *     tags={"Transaksi"},
     *     summary="Update status transaksi",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
        *     @OA\Response(
        *         response=200,
        *         description="Data Transaksi Berhasil Diambil",
        *         @OA\JsonContent(type="object",
        *             @OA\Property(property="status", type="boolean"),
        *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Transaction"))
        *         )
        *     )
        *     @OA\Response(
        *         response=200,
        *         description="Status berhasil diperbarui",
        *         @OA\JsonContent(type="object",
        *             @OA\Property(property="status", type="boolean"),
        *             @OA\Property(property="message", type="string", example="Status Transaksi Berhasil Diperbarui"),
        *             @OA\Property(property="data", ref="#/components/schemas/Transaction")
        *         )
        *     ),
        *     @OA\Response(response=404, description="Transaksi tidak ditemukan")
     * )
     */
        $lastTransaction = Transaction::withTrashed()->latest('id')->first();
        $lastNumber = $lastTransaction ? (int) substr($lastTransaction->invoice_code, 4) : 0;
        $invoiceCode = 'LND-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);


    /**
     * @OA\Post(
     *     path="/api/transactions/{id}/payment-proof",
     *     tags={"Transaksi"},
     *     summary="Upload bukti pembayaran",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(@OA\MediaType(mediaType="multipart/form-data", @OA\Schema(@OA\Property(property="payment_proof", type="string", format="binary")))),
        *     @OA\Response(
        *         response=200,
        *         description="Bukti pembayaran berhasil diupload",
        *         @OA\JsonContent(type="object",
        *             @OA\Property(property="status", type="boolean"),
        *             @OA\Property(property="message", type="string", example="Bukti Pembayaran Berhasil Diupload"),
        *             @OA\Property(property="data", ref="#/components/schemas/Transaction")
        *         )
        *     ),
        *     @OA\Response(response=404, description="Transaksi tidak ditemukan")
     * )
     */
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

        if (!$transaction) {
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

        if (!$transaction) {
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

        $transaction->update(['status' => $request->status]);

        return response()->json([
            'status' => true,
            'message' => 'Status Transaksi Berhasil Diperbarui',
            'data' => $transaction->load(['admin', 'customer.user', 'service']),
        ], 200);
    }

    public function uploadPaymentProof(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
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

    /**
     * @OA\Get(
     *     path="/api/status-laundry",
     *     tags={"Transaksi"},
     *     summary="Ambil Status Cucian Milik Pelanggan Yang Login",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Data Transaksi Berhasil Diambil")
     * )
    */
    public function statusLaundry(Request $request)
    {
        $user = $request->user();
        $customer = $user->customer;

        if (!$customer) {
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