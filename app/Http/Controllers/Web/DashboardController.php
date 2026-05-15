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
            'transaksi_terbaru' => Transaction::with(['customer.user', 'service'])->latest()->take(5)->get(),
            'pendapatan_bulanan' => $this->getPendapatanBulanan(),
        ];

        return view('admin.dashboard', compact('data'));
    }

    private function getPendapatanBulanan()
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