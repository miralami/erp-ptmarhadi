<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $order->status->value === 'ORDER_RECEIVED' || $order->status->value === 'PERJALANAN_MUAT' ? 'PO' : 'Surat Jalan' }} - {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Courier New', monospace; font-size: 12px; color: #000; margin: 30px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { font-size: 18px; margin: 0 0 5px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 11px; }
        .title { text-align: center; font-size: 14px; font-weight: bold; margin: 15px 0; text-decoration: underline; }
        .info-table { width: 100%; margin-bottom: 15px; }
        .info-table td { padding: 3px 5px; font-size: 11px; }
        .info-table td:first-child { width: 130px; font-weight: bold; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .items-table th, .items-table td { border: 1px solid #000; padding: 6px 8px; text-align: left; font-size: 11px; }
        .items-table th { background: #f0f0f0; font-weight: bold; text-align: center; }
        .items-table td.text-right { text-align: right; }
        .ttd-table { width: 100%; margin-top: 30px; }
        .ttd-table td { width: 33%; text-align: center; padding-top: 50px; font-size: 11px; }
        .ttd-line { border-top: 1px solid #000; width: 150px; margin: 0 auto 5px; padding-top: 5px; }
        .footer { text-align: center; margin-top: 20px; font-size: 10px; border-top: 1px solid #ccc; padding-top: 10px; }
        .photo-section { margin-top: 30px; }
        .photo-section h3 { font-size: 11px; font-weight: bold; border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 10px; }
        .photo-grid { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; }
        .photo-grid img { max-width: 300px; max-height: 200px; border: 1px solid #ccc; object-fit: cover; }
        .label-rute { font-size: 13px; font-weight: bold; margin: 8px 0; padding: 6px 10px; background: #f5f5f5; border-left: 4px solid #000; }
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

    @php $isBeforeDelivery = in_array($order->status->value, ['ORDER_RECEIVED', 'PERJALANAN_MUAT']) @endphp
    <div class="title">{{ $isBeforeDelivery ? 'PURCHASE ORDER' : 'SURAT JALAN' }}</div>
    <p style="text-align:center;font-size:12px;font-weight:bold;margin-bottom:10px;">No. {{ $order->order_number }}</p>

    <div class="label-rute">{{ $order->origin_city ?? 'Asal' }} → {{ $order->destination_city ?? 'Tujuan' }}</div>

    <table class="info-table">
        <tr><td>Tanggal</td><td>: {{ $order->order_date->format('d/m/Y') }}</td></tr>
        <tr><td>Kepada Yth</td><td>: {{ $order->customer->company_name }}</td></tr>
        @if ($order->customer->address)
            <tr><td>Alamat</td><td>: {{ $order->customer->address }}</td></tr>
        @endif
        <tr><td>Kota Asal</td><td>: {{ $order->origin_city ?? '-' }}</td></tr>
        <tr><td>Kota Tujuan</td><td>: {{ $order->destination_city ?? '-' }}</td></tr>
        @if ($order->customer_po_number)
            <tr><td>No. PO Customer</td><td>: {{ $order->customer_po_number }}</td></tr>
        @endif
        @if ($order->customer_spb_number)
            <tr><td>No. SPB Customer</td><td>: {{ $order->customer_spb_number }}</td></tr>
        @endif
        @if ($order->delivery)
            <tr><td>Driver</td><td>: {{ $order->delivery->driver_name ?? '-' }}</td></tr>
            @if ($order->delivery->vehicle)
                <tr><td>Kendaraan</td><td>: {{ $order->delivery->vehicle->plate_number }} ({{ $order->delivery->vehicle->type->label() }})</td></tr>
            @elseif ($order->delivery->vehicle_plate_manual)
                @php $vt = \App\Enums\VehicleType::tryFrom($order->delivery->vehicle_type_manual) @endphp
                <tr><td>Kendaraan</td><td>: {{ $order->delivery->vehicle_plate_manual }} ({{ $vt?->label() ?? $order->delivery->vehicle_type_manual ?? '-' }})</td></tr>
            @endif
        @endif
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="40">No</th>
                <th>Nama Barang</th>
                <th width="70">Unit (Max Slot)</th>
                <th width="90">Kubikasi (Peti)</th>
                <th width="100">Harga</th>
                <th width="100">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $index => $item)
                <tr>
                    <td style="text-align:center;">{{ $index + 1 }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td style="text-align:center;">{{ $item->unit }}{{ $item->max_slot ? ' / ' . $item->max_slot : '' }}</td>
                    <td style="text-align:center;">{{ $item->kubikasi ? number_format($item->kubikasi, 2) : '-' }}</td>
                    <td style="text-align:right;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td style="text-align:right;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="text-align:right;margin-bottom:10px;font-size:12px;">
        <strong>Total: Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
    </div>

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

    @if ($order->delivery)
    <div class="photo-section">
        @php
            $photoTypes = [
                'photo_muat' => 'Foto Muat',
                'photo_bongkar' => 'Foto Bongkar',
                'photo_surat_jalan' => 'Foto Surat Jalan',
            ];
        @endphp
        @foreach ($photoTypes as $key => $label)
            @if (!empty($order->delivery->$key))
                <h3>{{ $label }}</h3>
                <div class="photo-grid">
                    @foreach ($order->delivery->$key as $photo)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($photo) }}" alt="{{ $label }}"
                             onerror="this.style.display='none'">
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>
    @endif

    <div class="footer">
        Dicetak pada {{ now()->format('d/m/Y H:i') }} | {{ $companyName ?? 'PT MARHADI' }}
    </div>
</body>
</html>