<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;
use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();

        return response()->json([
            'status' => true,
            'data' => $services,
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/services",
     *     tags={"Service"},
     *     summary="List layanan",
     *     @OA\Response(
     *         response=200,
     *         description="Daftar layanan",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Service"))
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required|in:kiloan,satuan',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
        ], [
            'service_name.required' => 'Nama Layanan Wajib Diisi',
            'service_name.in' => 'Nama Layanan Harus Kiloan Atau Satuan',
            'price.required' => 'Harga Wajib Diisi',
            'price.numeric' => 'Harga Harus Berupa Angka',
            'price.min' => 'Harga Tidak Boleh Negatif',
            'unit.required' => 'Satuan Wajib Diisi',
            'unit.max' => 'Satuan Maksimal 20 Karakter',
        ]);

        $service = Service::create([
            'service_name' => $request->service_name,
            'price' => $request->price,
            'unit' => $request->unit,
        ]);


     /**
      * @OA\Post(
      *     path="/api/services",
      *     tags={"Service"},
      *     summary="Buat layanan",
      *     @OA\RequestBody(@OA\JsonContent(
      *         required={"service_name","price","unit"},
      *         @OA\Property(property="service_name", type="string"),
      *         @OA\Property(property="price", type="number"),
      *         @OA\Property(property="unit", type="string")
      *     )),
    *     @OA\Response(
    *         response=201,
    *         description="Layanan berhasil dibuat",
    *         @OA\JsonContent(type="object",
    *             @OA\Property(property="status", type="boolean"),
    *             @OA\Property(property="data", ref="#/components/schemas/Service")
    *         )
    *     )
      * )
      */
        return response()->json([
            'status' => true,
            'message' => 'Layanan Berhasil Ditambahkan',
            'data' => $service,
        ], 201);
    }


    /**
     * @OA\Get(
     *     path="/api/services/{id}",
     *     tags={"Service"},
     *     summary="Detail layanan",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
    *     @OA\Response(
    *         response=200,
    *         description="Detail layanan",
    *         @OA\JsonContent(type="object",
    *             @OA\Property(property="status", type="boolean"),
    *             @OA\Property(property="data", ref="#/components/schemas/Service")
    *         )
    *     ),
    *     @OA\Response(response=404, description="Layanan tidak ditemukan")
     * )
     */
    public function show($id)
    {
        $service = Service::find($id);


    /**
     * @OA\Delete(
     *     path="/api/services/{id}",
     *     tags={"Service"},
     *     summary="Hapus layanan",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
    *     @OA\Response(
    *         response=200,
    *         description="Layanan berhasil dihapus",
    *         @OA\JsonContent(type="object",
    *             @OA\Property(property="status", type="boolean")
    *         )
    *     ),
    *     @OA\Response(response=404, description="Layanan tidak ditemukan")
     * )
     */
        if (! $service) {
            return response()->json([
                'status' => false,
                'message' => 'Layanan Tidak Ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $service,
        ], 200);
    }

    public function update(Request $request, $id)
    {
    /**
     * @OA\Put(
     *     path="/api/services/{id}",
     *     tags={"Service"},
     *     summary="Update layanan",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(@OA\JsonContent(
     *         @OA\Property(property="service_name", type="string"),
     *         @OA\Property(property="price", type="number"),
     *         @OA\Property(property="unit", type="string")
     *     )),
     *     @OA\Response(response=200, description="Layanan Berhasil Diperbarui", @OA\JsonContent(type="object", @OA\Property(property="status", type="boolean"), @OA\Property(property="data", ref="#/components/schemas/Service")))
     * )
     */
        $service = Service::find($id);

        if (! $service) {
            return response()->json([
                'status' => false,
                'message' => 'Layanan Tidak Ditemukan',
            ], 404);
        }

        $request->validate([
            'service_name' => 'required|in:kiloan,satuan',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
        ], [
            'service_name.required' => 'Nama Layanan Wajib Diisi',
            'service_name.in' => 'Nama Layanan Harus Kiloan Atau Satuan',
            'price.required' => 'Harga Wajib Diisi',
            'price.numeric' => 'Harga Harus Berupa Angka',
            'price.min' => 'Harga Tidak Boleh Negatif',
            'unit.required' => 'Satuan Wajib Diisi',
            'unit.max' => 'Satuan Maksimal 20 Karakter',
        ]);

        $service->update([
            'service_name' => $request->service_name,
            'price' => $request->price,
            'unit' => $request->unit,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Layanan Berhasil Diperbarui',
            'data' => $service,
        ], 200);
    }

    public function destroy($id)
    {
    /**
     * @OA\Delete(
     *     path="/api/services/{id}",
     *     tags={"Service"},
     *     summary="Hapus layanan",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Layanan berhasil dihapus", @OA\JsonContent(type="object", @OA\Property(property="status", type="boolean")))
     * )
     */
        $service = Service::find($id);

        if (! $service) {
            return response()->json([
                'status' => false,
                'message' => 'Layanan Tidak Ditemukan',
            ], 404);
        }

        $service->delete();

        return response()->json([
            'status' => true,
            'message' => 'Layanan Berhasil Dihapus',
        ], 200);
    }
}