<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reportTitle }}</title>
    <style>
        body {
            
            
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }

        .logo-container {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 12px;
        }

        .header img {
            max-width: 120px;
            height: auto;
        }

        .title {
            font-size: 28px;
            font-weight: 700;
            color: #2563eb;
        }

        .period {
            font-size: 16px;
            color: #64748b;
        }

        .transactions-section {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        .section-header {
            padding: 20px;
            background-color: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
        }

        .section-title {
            margin: 0;
            font-size: 18px;
            color: #1f2937;
        }

        .table-container {
            overflow-x: auto;
            overflow-y: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th {
            background-color: #f8fafc;
            font-weight: 600;
            text-align: left;
            padding: 12px 20px;
            border-bottom: 2px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        td {
            padding: 12px 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        tr:hover {
            background-color: #f8fafc;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-completed {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                {{-- <div class="logo-container">
                    <img alt="Surya Arena Sport Hall" src="{{ url('images/logo_company.png') }}" width="60"
                        height="60">
                </div> --}}
                <div>
                    <div class="title">{{ $reportTitle }}</div>
                    <div class="period">
                        @if ($period == 'custom')
                            Periode: {{ $start_date ?? 'Seluruh Waktu' }} - {{ $end_date ?? 'Sekarang' }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="summary-section">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                <tr>
                    <td style="width: 33%; padding: 10px;">
                        <div class="summary-card"
                            style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; text-align: center;">
                            <h3 style="margin: 0; font-size: 14px; color: #64748b;">Total Transaksi</h3>
                            <p style="margin: 10px 0 0; font-size: 24px; font-weight: 600; color: #2563eb;">
                                {{ $transactions->count() }}</p>
                        </div>
                    </td>
                    <td style="width: 33%; padding: 10px;">
                        <div class="summary-card"
                            style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; text-align: center;">
                            <h3 style="margin: 0; font-size: 14px; color: #64748b;">Total Jumlah</h3>
                            <p style="margin: 10px 0 0; font-size: 24px; font-weight: 600; color: #2563eb;">Rp
                                {{ number_format($transactions->sum('total_bayar'), 0, ',', '.') }}</p>
                        </div>
                    </td>
                    <td style="width: 33%; padding: 10px;">
                        <div class="summary-card"
                            style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; text-align: center;">
                            <h3 style="margin: 0; font-size: 14px; color: #64748b;">Rata-rata Transaksi</h3>
                            <p style="margin: 10px 0 0; font-size: 24px; font-weight: 600; color: #2563eb;">Rp
                                {{ number_format($transactions->avg('total_bayar'), 0, ',', '.') }}</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="transactions-section">
            <div class="section-header">
                <h2 class="section-title">Detail Transaksi</h2>
            </div>
            <div class="table-container">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px; margin-top: 10px;">
                    <thead>
                        <tr>
                            <th
                                style="background-color: #2563eb; color: white; padding: 12px 15px; border-bottom: 2px solid #e5e7eb;">
                                No Transaksi</th>
                            <th
                                style="background-color: #2563eb; color: white; padding: 12px 15px; border-bottom: 2px solid #e5e7eb;">
                                Penyewa</th>
                            <th
                                style="background-color: #2563eb; color: white; padding: 12px 15px; border-bottom: 2px solid #e5e7eb;">
                                Tanggal</th>
                            <th
                                style="background-color: #2563eb; color: white; padding: 12px 15px; border-bottom: 2px solid #e5e7eb;">
                                Total Bayar</th>
                            <th
                                style="background-color: #2563eb; color: white; padding: 12px 15px; border-bottom: 2px solid #e5e7eb;">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 12px 15px;">{{ $transaction->no_trans }}</td>
                                <td style="padding: 12px 15px;">{{ $transaction->user->name }}</td>
                                <td style="padding: 12px 15px;">
                                    {{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y') }}</td>
                                <td style="padding: 12px 15px;">Rp
                                    {{ number_format($transaction->total_bayar, 0, ',', '.') }}</td>
                                <td style="padding: 12px 15px;">
                                    <span
                                        class="status-badge {{ strtolower($transaction->status) == 'completed' ? 'status-completed' : 'status-pending' }}">
                                        {{ $transaction->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="footer">
            <p>Dibuat pada {{ now()->format('d F Y, H:i') }}</p>
            <p>&copy; {{ date('Y') }} Surya Arena Sport Hall. Semua hak dilindungi.</p>
        </div>
    </div>
</body>

</html>
