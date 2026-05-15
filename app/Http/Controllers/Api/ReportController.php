<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Service;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();

        $totalPendapatan = Transaction::where('payment_status', 'paid')->sum('total_price');
        $pendapatanHariIni = Transaction::where('payment_status', 'paid')->whereDate('paid_at', $today)->sum('total_price');

        $totalTransaksi = Transaction::count();
        $transaksiHariIni = Transaction::whereDate('created_at', $today)->count();

        $totalCustomer = Customer::count();
        $totalLayanan = Service::count();

        $statusSummary = Transaction::selectRaw('status, count(*) as total')->groupBy('status')->get();

        $transaksiAntrian = Transaction::where('status', 'antrian')->count();
        $transaksiDiproses = Transaction::whereIn('status', ['dicuci', 'disetrika'])->count();
        $transaksiSiap = Transaction::where('status', 'siap diambil')->count();
        $transaksiSelesai = Transaction::where('status', 'diambil')->count();

        return response()->json([
            'status' => true,
            'data' => [
                'pendapatan' => [
                    'total' => $totalPendapatan,
                    'hari_ini' => $pendapatanHariIni,
                ],
                'transaksi' => [
                    'total' => $totalTransaksi,
                    'hari_ini' => $transaksiHariIni,
                    'antrian' => $transaksiAntrian,
                    'diproses' => $transaksiDiproses,
                    'siap' => $transaksiSiap,
                    'selesai' => $transaksiSelesai,
                ],
                'total_customer' => $totalCustomer,
                'total_layanan' => $totalLayanan,
                'status_summary' => $statusSummary,
            ],
        ], 200);
    }

    public function pendapatanPerBulan(Request $request)
    {
        $year = $request->year ?? Carbon::now()->year;

        $data = Transaction::where('payment_status', 'paid')->whereYear('paid_at', $year)->selectRaw('MONTH(paid_at) as bulan, SUM(total_price) as total')->groupBy('bulan')->orderBy('bulan')->get();

        $bulanLabel = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $result = [];

        for ($i = 1; $i <= 12; $i++) {
            $found = $data->firstWhere('bulan', $i);
            $result[] = [
                'bulan' => $bulanLabel[$i - 1],
                'total' => $found ? $found->total : 0,
            ];
        }

        return response()->json([
            'status' => true,
            'data' => $result,
            'year' => $year,
        ], 200);
    }

    public function transaksiPerHari(Request $request)
    {
        $month = $request->month ?? Carbon::now()->month;
        $year  = $request->year  ?? Carbon::now()->year;

        $data = Transaction::whereMonth('created_at', $month)->whereYear('created_at', $year)->selectRaw('DAY(created_at) as hari, COUNT(*) as total')->groupBy('hari')->orderBy('hari')->get();

        return response()->json([
            'status' => true,
            'data' => $data,
            'month' => $month,
            'year' => $year,
        ], 200);
    }

    public function layananPopuler()
    {
        $data = Transaction::with('service')->selectRaw('service_id, COUNT(*) as total_order, SUM(total_price) as total_pendapatan')->groupBy('service_id')->orderByDesc('total_order')->get();

        return response()->json([
            'status' => true,
            'data' => $data,
        ], 200);
    }

    public function riwayat(Request $request)
    {
        $query = Transaction::with(['admin', 'customer.user', 'service']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date   . ' 23:59:59',
            ]);
        }

        $transactions = $query->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $transactions,
        ], 200);
    }
}