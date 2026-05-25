<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk — {{ $transaction->invoice_code }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 2rem 1rem;
        }

        .action-bar {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            width: 100%;
            max-width: 360px;
        }
        .btn-print {
            flex: 1; display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.75rem; border-radius: 0.875rem; font-weight: 700; font-size: 0.875rem;
            cursor: pointer; border: none; color: #fff; transition: all 0.2s;
            background: linear-gradient(135deg, #059669, #10b981);
            box-shadow: 0 4px 12px rgba(16,185,129,0.35);
        }
        .btn-print:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(16,185,129,0.4); }

        .btn-pdf {
            flex: 1; display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.75rem; border-radius: 0.875rem; font-weight: 700; font-size: 0.875rem;
            cursor: pointer; border: none; color: #fff; transition: all 0.2s;
            background: linear-gradient(135deg, #dc2626, #ef4444);
            box-shadow: 0 4px 12px rgba(239,68,68,0.35);
        }
        .btn-pdf:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(239,68,68,0.4); }

        .btn-back {
            flex: 1; display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.75rem; border-radius: 0.875rem; font-weight: 700; font-size: 0.875rem;
            cursor: pointer; border: 2px solid #e2e8f0; color: #64748b;
            background: #fff; text-decoration: none; transition: all 0.2s;
        }
        .btn-back:hover { background: #f8fafc; }

        .struk {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.12);
            width: 100%;
            max-width: 360px;
            overflow: hidden;
        }

        .struk-header {
            background: linear-gradient(135deg, #0c4a6e, #0369a1, #0ea5e9);
            padding: 1.75rem 1.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .struk-header::before {
            content: '';
            position: absolute; top: -30px; right: -30px;
            width: 100px; height: 100px; border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }
        .struk-header::after {
            content: '';
            position: absolute; bottom: -20px; left: -20px;
            width: 70px; height: 70px; border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }
        .struk-logo {
            width: 52px; height: 52px; border-radius: 1rem;
            background: rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 0.75rem;
        }
        .struk-logo i { color: #fff; font-size: 1.4rem; }
        .struk-brand { color: #0f172a; font-size: 1.25rem; font-weight: 800; letter-spacing: -0.02em; }
        .struk-tagline { color: rgba(186,230,253,0.9); font-size: 0.7rem; margin-top: 0.15rem; }

        .invoice-badge {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 0.75rem;
            padding: 0.5rem 1rem;
            display: inline-block;
            margin-top: 1rem;
        }
        .invoice-label { color: rgba(186,230,253,0.8); font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.1em; }
        .invoice-code { color: #fff; font-size: 1.1rem; font-weight: 800; font-family: 'Courier New', monospace; letter-spacing: 0.05em; }
        .invoice-date { color: rgba(186,230,253,0.8); font-size: 0.65rem; margin-top: 0.2rem; }

        .status-chip {
            display: inline-flex; align-items: center; gap: 0.35rem;
            padding: 0.3rem 0.75rem; border-radius: 999px;
            font-size: 0.65rem; font-weight: 700; margin-top: 0.75rem;
        }

        .struk-body { padding: 1.25rem 1.5rem; }

        .section-title {
            font-size: 0.65rem; font-weight: 700; color: #94a3b8;
            text-transform: uppercase; letter-spacing: 0.08em;
            margin-bottom: 0.6rem;
        }

        .info-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 0.4rem 0;
        }
        .info-label { color: #64748b; font-size: 0.78rem; }
        .info-value { color: #0f172a; font-size: 0.78rem; font-weight: 600; text-align: right; }

        .divider {
            border: none; border-top: 1.5px dashed #e2e8f0;
            margin: 1rem 0;
        }
        .divider-solid {
            border: none; border-top: 2px solid #f1f5f9;
            margin: 1rem 0;
        }

        .customer-card {
            background: #f5f3ff;
            border-radius: 0.875rem;
            padding: 0.875rem 1rem;
            margin-bottom: 0.5rem;
        }
        .customer-name { font-weight: 700; color: #0f172a; font-size: 0.875rem; }
        .customer-phone { color: #64748b; font-size: 0.72rem; margin-top: 0.15rem; }

        .total-box {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 1.5px solid #86efac;
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            display: flex; justify-content: space-between; align-items: center;
            margin: 1rem 0;
        }
        .total-label { font-weight: 700; color: #166534; font-size: 0.875rem; }
        .total-amount { font-weight: 800; color: #15803d; font-size: 1.35rem; }

        .payment-badge {
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.6rem; border-radius: 0.75rem;
            font-weight: 700; font-size: 0.8rem; margin-bottom: 1rem;
        }
        .paid { background: #d1fae5; color: #065f46; }
        .unpaid { background: #ffedd5; color: #7c2d12; }

        .barcode-wrap {
            display: flex; justify-content: center; gap: 2px;
            margin: 0.75rem 0;
            opacity: 0.15;
        }
        .barcode-bar {
            background: #334155; border-radius: 1px;
        }

        .struk-footer {
            background: #f8fafc;
            border-top: 1.5px dashed #e2e8f0;
            padding: 1rem 1.5rem;
            text-align: center;
        }
        .footer-text { color: #94a3b8; font-size: 0.68rem; line-height: 1.6; }
        .footer-date { color: #cbd5e1; font-size: 0.62rem; margin-top: 0.35rem; }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .action-bar { display: none !important; }
            .struk {
                border-radius: 0;
                box-shadow: none;
                max-width: 100%;
            }
            .struk-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .total-box { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .customer-card { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .payment-badge { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }

        @keyframes fadeIn { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .struk { animation: fadeIn 0.4s ease-out; }
    </style>
</head>
<body>

    <div class="action-bar">
        <a href="{{ route('admin.transactions.show', ['id' => $transaction->id]) }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button class="btn-print" onclick="window.print()">
            <i class="fas fa-print"></i> Print
        </button>
        <button class="btn-pdf" onclick="downloadPDF()">
            <i class="fas fa-file-pdf"></i> PDF
        </button>
    </div>

    <div class="struk" id="strukPrint">

        <div class="struk-header">
            <div class="struk-logo">
                <i class="fas fa-shirt"></i>
            </div>
            <p class="struk-brand">LaundryPOS</p>
            <p class="struk-tagline">Sistem Manajemen Laundry Modern</p>

            <div class="invoice-badge">
                <p class="invoice-label">No. Invoice</p>
                <p class="invoice-code">{{ $transaction->invoice_code }}</p>
                <p class="invoice-date">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
            </div>

            @php
                $chipBg = match($transaction->status) {
                    'antrian' => 'rgba(251,191,36,0.25)',
                    'dicuci' => 'rgba(96,165,250,0.25)',
                    'disetrika' => 'rgba(192,132,252,0.25)',
                    'siap diambil' => 'rgba(52,211,153,0.25)',
                    'diambil' => 'rgba(148,163,184,0.25)',
                    default => 'rgba(148,163,184,0.25)',
                };
                $chipColor = match($transaction->status) {
                    'antrian' => '#fbbf24',
                    'dicuci' => '#60a5fa',
                    'disetrika' => '#c084fc',
                    'siap diambil' => '#34d399',
                    'diambil' => '#94a3b8',
                    default => '#94a3b8',
                };
                $chipIcon = match($transaction->status) {
                    'antrian' => 'clock',
                    'dicuci' => 'soap',
                    'disetrika' => 'wind',
                    'siap diambil' => 'box-open',
                    'diambil' => 'flag-checkered',
                    default => 'circle',
                };
            @endphp
            <div>
                <span class="status-chip" style="background:{{ $chipBg }};color:{{ $chipColor }}">
                    <i class="fas fa-{{ $chipIcon }}"></i>
                    {{ ucfirst($transaction->status) }}
                </span>
            </div>
        </div>

        <div class="struk-body">

            <p class="section-title"><i class="fas fa-user" style="margin-right:4px"></i> Pelanggan</p>
            <div class="customer-card">
                <p class="customer-name">{{ $transaction->customer->user->name }}</p>
                <p class="customer-phone">
                    <i class="fas fa-phone" style="font-size:0.6rem;margin-right:4px"></i>
                    {{ $transaction->customer->phone }}
                </p>
            </div>

            <hr class="divider">

            <p class="section-title"><i class="fas fa-receipt" style="margin-right:4px"></i> Detail Layanan</p>

            <div class="info-row">
                <span class="info-label">Layanan</span>
                <span class="info-value capitalize">{{ $transaction->service->service_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Jumlah</span>
                <span class="info-value">{{ $transaction->service_unit }} {{ $transaction->service->unit }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Harga per {{ $transaction->service->unit }}</span>
                <span class="info-value">Rp {{ number_format($transaction->service->price, 0, ',', '.') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Metode Pembayaran</span>
                <span class="info-value uppercase">{{ $transaction->payment_method }}</span>
            </div>

            <hr class="divider">

            <div class="total-box">
                <span class="total-label">TOTAL</span>
                <span class="total-amount">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
            </div>

            @if($transaction->payment_status === 'paid')
            <div class="payment-badge paid">
                <i class="fas fa-check-circle"></i>
                LUNAS
                @if($transaction->paid_at)
                — {{ \Carbon\Carbon::parse($transaction->paid_at)->format('d M Y H:i') }}
                @endif
            </div>
            @else
            <div class="payment-badge unpaid">
                <i class="fas fa-clock"></i>
                BELUM LUNAS
            </div>
            @endif

            <div class="barcode-wrap">
                @php
                    $bars = [2,4,3,6,2,5,3,7,4,2,6,3,5,2,4,6,3,5,2,7,4,3,6,2,5,3,4,2,6,5];
                @endphp
                @foreach($bars as $h)
                <div class="barcode-bar" style="width:{{ rand(2,4) }}px;height:{{ $h * 4 }}px"></div>
                @endforeach
            </div>

        </div>

        <div class="struk-footer">
            <p class="footer-text">
                Terima Kasih Telah Mempercayakan Cucian Anda<br>
                Kepada <strong>LaundryPOS</strong> 🙏
            </p>
            <p class="footer-date">
                Dicetak : {{ now()->format('d M Y, H:i') }}
                &nbsp;·&nbsp;
                Admin : {{ auth()->user()->name }}
            </p>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            const element = document.getElementById('strukPrint');
            const opt = {
                margin: [0, 0, 0, 0],
                filename: 'Struk-{{ $transaction->invoice_code }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'mm', format: [90, 200], orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        }

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === '1') {
            window.onload = () => setTimeout(() => window.print(), 500);
        }
    </script>
</body>
</html>