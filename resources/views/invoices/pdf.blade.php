<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #1e293b;
            padding: 40px;
        }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; padding-bottom: 20px; border-bottom: 2px solid #6366f1; }
        .company h1 { font-size: 20px; font-weight: 700; color: #1e293b; }
        .company p { font-size: 11px; color: #64748b; margin-top: 2px; }
        .invoice-title { text-align: right; }
        .invoice-title h2 { font-size: 24px; font-weight: 700; color: #6366f1; }
        .invoice-title p { font-size: 11px; color: #64748b; margin-top: 2px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 40px; }
        .info-box h3 { font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #6366f1; margin-bottom: 8px; }
        .info-box p { font-size: 11px; color: #334155; margin-bottom: 2px; }
        .info-box .label { font-size: 9px; color: #94a3b8; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        table thead th { background: #f1f5f9; padding: 10px 12px; text-align: left; font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #475569; }
        table tbody td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        table tbody td:last-child, table thead th:last-child { text-align: right; }
        .totals { width: 300px; margin-left: auto; margin-bottom: 40px; }
        .totals .row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 11px; }
        .totals .row.subtotal { border-top: 1px solid #e2e8f0; }
        .totals .row.ppn { color: #6366f1; }
        .totals .row.total { border-top: 2px solid #1e293b; padding-top: 8px; font-weight: 700; font-size: 14px; }
        .totals .row.paid { color: #059669; }
        .totals .row.remaining { color: #dc2626; }
        .payment-summary { margin-top: 8px; }
        .payment-summary h3 { font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #6366f1; margin-bottom: 6px; }
        .payment-summary p { font-size: 11px; color: #334155; }
        .bank-info { margin-top: 30px; padding: 16px; background: #f8fafc; border-radius: 6px; }
        .bank-info h3 { font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #6366f1; margin-bottom: 6px; }
        .bank-info p { font-size: 11px; color: #475569; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 9px; color: #94a3b8; }
        .ttd { margin-top: 40px; text-align: right; }
        .ttd p { font-size: 11px; color: #334155; }
        .ttd .space { height: 60px; }
        @media print {
            body { padding: 20px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align:right;margin-bottom:20px;">
        <button onclick="window.print()" style="padding:8px 20px;background:#6366f1;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:12px;">Print / PDF</button>
    </div>

    <div class="header">
        <div class="company">
            <h1>{{ $company['company_name'] ?? 'PT Marhadi Distribution Center' }}</h1>
            <p>{{ $company['address'] ?? 'Jl. Raya Marhadi No. 1, Jakarta' }}</p>
            @if (!empty($company['phone']) || !empty($company['email']))
                <p>{{ $company['phone'] ? 'Telp: ' . $company['phone'] : '' }}{{ $company['phone'] && $company['email'] ? ' | ' : '' }}{{ $company['email'] ? 'Email: ' . $company['email'] : '' }}</p>
            @endif
            @if (!empty($company['npwp']))
                <p>NPWP: {{ $company['npwp'] }}</p>
            @endif
        </div>
        <div class="invoice-title">
            <h2>INVOICE</h2>
            <p>{{ $invoice->invoice_number }}</p>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-box">
            <h3>Kepada</h3>
            <p style="font-weight:600;">{{ $invoice->customer?->company_name ?? '-' }}</p>
            <p>{{ $invoice->customer->address ?? '-' }}</p>
            <p>{{ $invoice->customer->email ?? '-' }}</p>
            <p>{{ $invoice->customer->phone ?? '-' }}</p>
            @if ($invoice->customer?->npwp)
                <p>NPWP: {{ $invoice->customer->npwp }}</p>
            @endif
        </div>
        <div class="info-box" style="text-align:right;">
            <h3>Detail Invoice</h3>
            <p><span class="label">Tgl Invoice:</span> {{ $invoice->invoice_date->format('d/m/Y') }}</p>
            <p><span class="label">Jatuh Tempo:</span> {{ $invoice->due_date->format('d/m/Y') }}</p>
            <p><span class="label">Status:</span> {{ $invoice->status->label() }}</p>
            <p><span class="label">No. Order:</span> {{ $invoice->order->order_number ?? '-' }}</p>
            @if ($invoice->customer_po_number)
                <p><span class="label">No. PO:</span> {{ $invoice->customer_po_number }}</p>
            @endif
            @if ($invoice->customer_spb_number)
                <p><span class="label">No. SPB:</span> {{ $invoice->customer_spb_number }}</p>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:50%;">Barang</th>
                <th style="text-align:center;">Unit</th>
                <th style="text-align:right;">Harga Satuan</th>
                <th style="text-align:right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoice->order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td style="text-align:center;">{{ $item->unit }}</td>
                    <td style="text-align:right;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td style="text-align:right;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;padding:20px;color:#94a3b8;">Tidak ada item.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="totals">
        <div class="row subtotal">
            <span>Subtotal (Sebelum PPN)</span>
            <span>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
        </div>
        <div class="row ppn">
            <span>PPN {{ $invoice->ppn_rate }}%</span>
            <span>Rp {{ number_format($invoice->ppn_amount, 0, ',', '.') }}</span>
        </div>
        <div class="row total">
            <span>Total</span>
            <span>Rp {{ number_format($invoice->invoice_total, 0, ',', '.') }}</span>
        </div>
        <div class="row paid">
            <span>Dibayar</span>
            <span>Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}</span>
        </div>
        <div class="row remaining">
            <span>Sisa</span>
            <span>Rp {{ number_format($invoice->remaining, 0, ',', '.') }}</span>
        </div>
    </div>

    @if ($invoice->payments->count())
        <div class="payment-summary">
            <h3>Riwayat Pembayaran</h3>
            <table>
                <thead>
                    <tr>
                        <th>No. Pembayaran</th>
                        <th>Tanggal</th>
                        <th>Metode</th>
                        <th style="text-align:right;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_number }}</td>
                            <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                            <td>{{ $payment->payment_method->label() }}</td>
                            <td style="text-align:right;">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="bank-info">
        <h3>Informasi Bank</h3>
        <p><strong>{{ $company['bank_name'] ?? 'Bank' }}</strong></p>
        <p>No. Rekening: {{ $company['bank_account'] ?? '-' }}</p>
        <p>Cabang: {{ $company['bank_branch'] ?? '-' }}</p>
        <p>A/N: {{ $company['company_name'] ?? 'PT Marhadi' }}</p>
    </div>

    <div class="ttd">
        <p>{{ $company['company_name'] ?? 'PT Marhadi' }}</p>
        <div class="space"></div>
        <p>( {{ $company['signature_name'] ?? '_________________' }} )</p>
    </div>

    @if ($invoice->notes)
        <div style="margin-top:30px;padding:16px;background:#f8fafc;border-radius:6px;">
            <p style="font-size:9px;font-weight:600;text-transform:uppercase;color:#6366f1;margin-bottom:4px;">Catatan</p>
            <p style="font-size:11px;color:#475569;">{{ $invoice->notes }}</p>
        </div>
    @endif

    <div class="footer">
        <p>{{ $company['company_name'] ?? 'PT Marhadi' }} — Terima kasih atas kepercayaan Anda</p>
        <p style="margin-top:4px;">Invoice ini sah dan diterbitkan secara elektronik.</p>
    </div>
</body>
</html>
