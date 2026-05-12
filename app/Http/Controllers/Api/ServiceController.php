<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();

        return response()->json([
            'status' => true,
            'data'   => $services,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required|in:kiloan,satuan',
            'price'        => 'required|numeric|min:0',
            'unit'         => 'required|string|max:20',
        ], [
            'service_name.required' => 'Nama Layanan Wajib Diisi',
            'service_name.in'       => 'Nama Layanan Harus Kiloan Atau Satuan',
            'price.required'        => 'Harga Wajib Diisi',
            'price.numeric'         => 'Harga Harus Berupa Angka',
            'price.min'             => 'Harga Tidak Boleh Negatif',
            'unit.required'         => 'Satuan Wajib Diisi',
            'unit.max'              => 'Satuan Maksimal 20 Karakter',
        ]);

        $service = Service::create([
            'service_name' => $request->service_name,
            'price'        => $request->price,
            'unit'         => $request->unit,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Layanan Berhasil Ditambahkan',
            'data'    => $service,
        ], 201);
    }

    public function show($id)
    {
        $service = Service::find($id);

        if (! $service) {
            return response()->json([
                'status'  => false,
                'message' => 'Layanan Tidak Ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $service,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $service = Service::find($id);

        if (! $service) {
            return response()->json([
                'status'  => false,
                'message' => 'Layanan Tidak Ditemukan',
            ], 404);
        }

        $request->validate([
            'service_name' => 'required|in:kiloan,satuan',
            'price'        => 'required|numeric|min:0',
            'unit'         => 'required|string|max:20',
        ], [
            'service_name.required' => 'Nama Layanan Wajib Diisi',
            'service_name.in'       => 'Nama Layanan Harus Kiloan Atau Satuan',
            'price.required'        => 'Harga Wajib Diisi',
            'price.numeric'         => 'Harga Harus Berupa Angka',
            'price.min'             => 'Harga Tidak Boleh Negatif',
            'unit.required'         => 'Satuan Wajib Diisi',
            'unit.max'              => 'Satuan Maksimal 20 Karakter',
        ]);

        $service->update([
            'service_name' => $request->service_name,
            'price'        => $request->price,
            'unit'         => $request->unit,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Layanan Berhasil Diperbarui',
            'data'    => $service,
        ], 200);
    }

    public function destroy($id)
    {
        $service = Service::find($id);

        if (! $service) {
            return response()->json([
                'status'  => false,
                'message' => 'Layanan Tidak Ditemukan',
            ], 404);
        }

        $service->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Layanan Berhasil Dihapus',
        ], 200);
    }
}