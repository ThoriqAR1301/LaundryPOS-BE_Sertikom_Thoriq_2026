<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Service;
use Carbon\Carbon;

class ReportWebController extends Controller
{
    public function index()
    {
        $year = request('year', Carbon::now()->year);
        $month = request('month', Carbon::now()->month);

        $pendapatanBulanan = $this->getPendapatanBulanan($year);
        $transaksiHarian = $this->getTransaksiHarian($month, $year);
        $layananPopuler = Transaction::with('service')->selectRaw('service_id, COUNT(*) as total_order, SUM(total_price) as total_pendapatan')->groupBy('service_id')->orderByDesc('total_order')->get();

        $totalPendapatan = Transaction::where('payment_status', 'paid')->sum('total_price');
        $totalTransaksi = Transaction::count();
        $totalCustomer = Customer::count();

        return view('admin.reports.index', compact(
            'pendapatanBulanan', 'transaksiHarian',
            'layananPopuler', 'totalPendapatan',
            'totalTransaksi', 'totalCustomer',
            'year', 'month'
        ));
    }

    private function getPendapatanBulanan($year)
    {
        $raw = Transaction::where('payment_status', 'paid')->whereYear('paid_at', $year)->selectRaw('MONTH(paid_at) as bulan, SUM(total_price) as total')->groupBy('bulan')->orderBy('bulan')->get();

        $result = array_fill(0, 12, 0);
        foreach ($raw as $item) $result[$item->bulan - 1] = (float) $item->total;
        return $result;
    }

    private function getTransaksiHarian($month, $year)
    {
        return Transaction::whereMonth('created_at', $month)->whereYear('created_at', $year)->selectRaw('DAY(created_at) as hari, COUNT(*) as total')->groupBy('hari')->orderBy('hari')->get();
    }
}