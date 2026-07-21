<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Jalan - {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Courier New', monospace; font-size: 12px; color: #000; margin: 30px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { font-size: 18px; margin: 0 0 5px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 11px; }
        .title { text-align: center; font-size: 14px; font-weight: bold; margin: 15px 0; text-decoration: underline; }
        .info-table { width: 100%; margin-bottom: 15px; }
        .info-table td { padding: 3px 5px; font-size: 11px; }
        .info-table td:first-child { width: 150px; font-weight: bold; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .items-table th, .items-table td { border: 1px solid #000; padding: 6px 8px; text-align: left; font-size: 11px; }
        .items-table th { background: #f0f0f0; font-weight: bold; text-align: center; }
        .items-table td.text-right { text-align: right; }
        .ttd-table { width: 100%; margin-top: 30px; }
        .ttd-table td { width: 33%; text-align: center; padding-top: 50px; font-size: 11px; }
        .ttd-line { border-top: 1px solid #000; width: 150px; margin: 0 auto 5px; padding-top: 5px; }
        .footer { text-align: center; margin-top: 20px; font-size: 10px; border-top: 1px solid #ccc; padding-top: 10px; }
        .note { font-size: 10px; margin-top: 10px; font-style: italic; }
        @media print {
            body { margin: 15px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom:15px;">
        <button onclick="window.print()" style="padding:8px 20px;background:#4f46e5;color:white;border:none;border-radius:6px;cursor:pointer;font-size:13px;">Cetak / Print</button>
        <button onclick="window.close()" style="padding:8px 20px;background:#e2e8f0;color:#475569;border:none;border-radius:6px;cursor:pointer;font-size:13px;margin-left:8px;">Tutup</button>
    </div>

    <div class="header">
        <h1>{{ $companyName ?? 'PT MARHADI' }}</h1>
        <p>{{ $companyAddress ?? '' }}</p>
        <p>Telp: {{ $companyPhone ?? '' }} | Email: {{ $companyEmail ?? '' }}</p>
    </div>

    <div class="title">SURAT JALAN</div>

    <table class="info-table">
        <tr><td>No. Surat Jalan</td><td>: {{ $order->order_number }}</td></tr>
        <tr><td>Tanggal</td><td>: {{ $order->order_date->format('d/m/Y') }}</td></tr>
        <tr><td>Kepada Yth</td><td>: {{ $order->customer->company_name }}</td></tr>
        @if ($order->customer->address)
            <tr><td>Alamat</td><td>: {{ $order->customer->address }}</td></tr>
        @endif
        <tr><td>Rute</td><td>: {{ $order->origin_city ?? '-' }} → {{ $order->destination_city ?? '-' }}</td></tr>
        @if ($order->customer_po_number)
            <tr><td>No. PO</td><td>: {{ $order->customer_po_number }}</td></tr>
        @endif
        @if ($order->delivery)
            <tr><td>Driver</td><td>: {{ $order->delivery->driver_name ?? '-' }}</td></tr>
            @if ($order->delivery->vehicle)
                <tr><td>Kendaraan</td><td>: {{ $order->delivery->vehicle->plate_number }} ({{ $order->delivery->vehicle->brand }} {{ $order->delivery->vehicle->model }})</td></tr>
            @elseif ($order->delivery->vehicle_plate_manual)
                <tr><td>Kendaraan</td><td>: {{ $order->delivery->vehicle_plate_manual }} ({{ $order->delivery->vehicle_type_manual ?? '-' }})</td></tr>
            @endif
        @endif
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="40">No</th>
                <th>Nama Barang</th>
                <th width="60">Unit</th>
                <th width="80">Kubikasi</th>
                <th width="100">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $index => $item)
                <tr>
                    <td style="text-align:center;">{{ $index + 1 }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td style="text-align:center;">{{ $item->unit }}</td>
                    <td style="text-align:center;">{{ $item->kubikasi ? number_format($item->kubikasi, 2) : '-' }}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="font-size:11px;"><strong>Catatan:</strong> {{ $order->notes ?? '-' }}</p>

    <table class="ttd-table">
        <tr>
            <td>
                <p>Pengirim</p>
                <div class="ttd-line"></div>
                <p>( _________________ )</p>
            </td>
            <td>
                <p>Penerima</p>
                <div class="ttd-line"></div>
                <p>( _________________ )</p>
            </td>
            <td>
                <p>Mengetahui,</p>
                <div class="ttd-line"></div>
                <p>( {{ $signatureName ?? '_________________' }} )</p>
            </td>
        </tr>
    </table>

    @if ($order->delivery?->photo_surat_jalan)
        <div class="note">
            <p><strong>Foto Surat Jalan:</strong></p>
            @foreach ($order->delivery->photo_surat_jalan as $photo)
                <img src="{{ $photo }}" style="max-width:200px;max-height:200px;margin:5px;border:1px solid #ccc;">
            @endforeach
        </div>
    @endif

    <div class="footer">
        Dicetak pada {{ now()->format('d/m/Y H:i') }} | {{ $companyName ?? 'PT MARHADI' }}
    </div>
</body>
</html>
