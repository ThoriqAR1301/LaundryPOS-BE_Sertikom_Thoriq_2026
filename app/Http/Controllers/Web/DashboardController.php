<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Service;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $data = [
            'total_pendapatan' => Transaction::where('payment_status', 'paid')->sum('total_price'),
            'pendapatan_hari_ini' => Transaction::where('payment_status', 'paid')->whereDate('paid_at', $today)->sum('total_price'),
            'total_transaksi' => Transaction::count(),
            'transaksi_hari_ini' => Transaction::whereDate('created_at', $today)->count(),
            'total_customer' => Customer::count(),
            'total_layanan' => Service::count(),

            'antrian' => Transaction::where('status', 'antrian')->count(),
            'diproses' => Transaction::whereIn('status', ['dicuci', 'disetrika'])->count(),
            'siap' => Transaction::where('status', 'siap diambil')->count(),
            'selesai' => Transaction::where('status', 'diambil')->count(),

            'summary' => [
                'lunas' => Transaction::where('payment_status', 'paid')->count(),
                'pending' => Transaction::where('payment_status', 'pending')->count(),
                'total' => Transaction::count(),
            ],

            'pendapatan_bulanan' => $this->getPendapatanBulanan(),

            'transaksi_harian' => Transaction::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->selectRaw('DAY(created_at) as hari, COUNT(*) as total')->groupBy('hari')->orderBy('hari')->get(),

            'layanan_populer' => Transaction::with('service')->selectRaw('service_id, COUNT(*) as total_order, SUM(total_price) as total_pendapatan')->groupBy('service_id')->orderByDesc('total_order')->take(5)->get(),

            'transaksi_terbaru' => Transaction::with(['customer.user', 'service'])->latest()->take(5)->get(),

            'transactions' => Transaction::with(['customer.user', 'service'])->when(request('search'), function ($q, $s) { $q->whereHas('customer.user', fn($q) => $q->where('name', 'like', "%$s%"))->orWhere('invoice_code', 'like', "%$s%"); })->when(request('status'), fn($q, $s) => $q->where('status', $s))->when(request('payment_status'), fn($q, $s) => $q->where('payment_status', $s))->latest()->paginate(10),
        ];

        return view('admin.dashboard', compact('data'));
    }

    private function getPendapatanBulanan(): array
    {
        $year = Carbon::now()->year;

        $raw = Transaction::where('payment_status', 'paid')->whereYear('paid_at', $year)->selectRaw('MONTH(paid_at) as bulan, SUM(total_price) as total')->groupBy('bulan')->orderBy('bulan')->get();

        $result = array_fill(0, 12, 0);
        foreach ($raw as $item) {
            $result[$item->bulan - 1] = (float) $item->total;
        }

        return $result;
    }

    public function profile()
    {
        return view('admin.profile');
    }
}