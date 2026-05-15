<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class ServiceWebController extends Controller
{
    public function index()
    {
        $services = Service::latest()->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required|in:kiloan,satuan',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
        ]);

        Service::create($request->only('service_name', 'price', 'unit'));
        return redirect()->route('admin.services.index')->with('success', 'Layanan Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $request->validate([
            'service_name' => 'required|in:kiloan,satuan',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
        ]);

        $service->update($request->only('service_name', 'price', 'unit'));
        return redirect()->route('admin.services.index')->with('success', 'Layanan Berhasil Diperbarui');
    }

    public function destroy($id)
    {
        Service::findOrFail($id)->delete();
        return redirect()->route('admin.services.index')->with('success', 'Layanan Berhasil Dihapus');
    }
}