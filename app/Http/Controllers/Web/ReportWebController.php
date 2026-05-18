<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Customer;

use Carbon\Carbon;

class ReportWebController extends Controller
{
    public function index()
    {
        $year = (int) request('year',  date('Y'));
        $month = (int) request('month', date('n'));

        $totalPendapatan = (float) Transaction::where('payment_status', 'paid')->sum('total_price');
        $totalTransaksi = Transaction::count();
        $totalCustomer = Customer::count();

        $pendapatanBulanan = $this->getPendapatanBulanan($year);

        $transaksiHarian = Transaction::whereYear('created_at', $year)->whereMonth('created_at', $month)->selectRaw('DAY(created_at) as hari, COUNT(*) as total')->groupBy('hari')->orderBy('hari')->get();

        $layananPopuler = Transaction::with('service')->selectRaw('service_id, COUNT(*) as total_order, SUM(total_price) as total_pendapatan')->groupBy('service_id')->orderByDesc('total_order')->take(10)->get();

        return view('admin.reports.index', compact(
            'year', 'month',
            'totalPendapatan', 'totalTransaksi', 'totalCustomer',
            'pendapatanBulanan', 'transaksiHarian', 'layananPopuler',
        ));
    }

    private function getPendapatanBulanan(int $year): array
    {
        $raw = Transaction::where('payment_status', 'paid')->whereYear('created_at', $year)->selectRaw('MONTH(created_at) as bulan, SUM(total_price) as total')->groupBy('bulan')->orderBy('bulan')->get();

        $result = array_fill(0, 12, 0);
        foreach ($raw as $item) {
            $result[$item->bulan - 1] = (float) $item->total;
        }

        return $result;
    }

    
}