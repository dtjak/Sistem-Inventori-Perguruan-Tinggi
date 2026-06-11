<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order - {{ $po->nomor_po }}</title>
    <style>
        * { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; }
        body { margin: 15px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 2px double #333; padding-bottom: 8px; }
        .header h2 { margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase; color: #111; }
        .header p { margin: 3px 0 0 0; font-size: 10px; color: #666; }
        .meta-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .meta-table td { padding: 4px 0; vertical-align: top; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .items-table th { background-color: #f5f5f5; border: 1px solid #ddd; padding: 7px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 9px; }
        .items-table td { border: 1px solid #ddd; padding: 7px; }
        .items-table tr:nth-child(even) { background-color: #fafafa; }
        .notes { border: 1px solid #ddd; padding: 10px; background-color: #fcfcfc; margin-bottom: 30px; border-radius: 4px; }
        .notes-title { font-weight: bold; margin-bottom: 4px; font-size: 10px; text-transform: uppercase; color: #555; }
        .footer-table { width: 100%; margin-top: 40px; border-collapse: collapse; page-break-inside: avoid; }
        .footer-table td { text-align: center; width: 33.3%; }
        .signature-space { height: 60px; }
        .font-monospace { font-family: 'Courier New', Courier, monospace; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Purchase Order (PO)</h2>
        <p>Sistem Informasi Inventori Perguruan Tinggi</p>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 18%;" class="fw-bold">Nomor PO</td>
            <td style="width: 32%;">: <span class="font-monospace fw-bold">{{ $po->nomor_po }}</span></td>
            <td style="width: 18%;" class="fw-bold">Supplier Rekanan</td>
            <td style="width: 32%;">: <span class="fw-bold">{{ $po->supplier->nama_supplier ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="fw-bold">Tanggal PO</td>
            <td>: {{ $po->tanggal->format('d/m/Y') }}</td>
            <td class="fw-bold">Alamat Supplier</td>
            <td>: {{ $po->supplier->alamat ?? '-' }}</td>
        </tr>
        <tr>
            <td class="fw-bold">Tgl Estimasi Kirim</td>
            <td>: {{ $po->tanggal_kirim ? $po->tanggal_kirim->format('d/m/Y') : '-' }}</td>
            <td class="fw-bold">PIC Supplier</td>
            <td>: {{ $po->supplier->pic ?? '-' }} (Telp: {{ $po->supplier->telepon ?? '-' }})</td>
        </tr>
        <tr>
            <td class="fw-bold">Status</td>
            <td>: <span style="text-transform: uppercase; font-weight: bold;">{{ $po->status }}</span></td>
            <td colspan="2"></td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%">No</th>
                <th style="width: 15%">Kode Barang</th>
                <th style="width: 40%">Nama Barang</th>
                <th class="text-center" style="width: 10%">Qty</th>
                <th class="text-right" style="width: 15%">Harga Satuan</th>
                <th class="text-right" style="width: 15%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($po->details as $index => $detail)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-monospace">{{ $detail->barang->kode_barang }}</td>
                    <td>{{ $detail->barang->nama_barang }}</td>
                    <td class="text-center">{{ $detail->qty }} {{ $detail->barang->satuan }}</td>
                    <td class="text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td class="text-right fw-bold">Rp {{ number_format($detail->qty * $detail->harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr style="background-color: #f5f5f5; font-weight: bold;">
                <td colspan="5" class="text-right">Total Pembelian:</td>
                <td class="text-right">Rp {{ number_format($po->total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    @if($po->catatan)
        <div class="notes">
            <div class="notes-title">Catatan / Ketentuan Pembelian</div>
            <div>{{ $po->catatan }}</div>
        </div>
    @endif

    <table class="footer-table">
        <tr>
            <td>
                <p>Supplier Rekanan</p>
                <div class="signature-space"></div>
                <p class="fw-bold">( _______________________ )</p>
                <p>PIC Supplier</p>
            </td>
            <td>
                <p>Dibuat Oleh,</p>
                <div class="signature-space"></div>
                <p class="fw-bold">( {{ $po->dibuatOleh->name ?? '___________________' }} )</p>
                <p>Staff Purchasing</p>
            </td>
            <td>
                <p>Menyetujui,</p>
                <div class="signature-space"></div>
                <p class="fw-bold">( {{ $po->approvedFinance->name ?? '___________________' }} )</p>
                <p>Finance / Rektorat</p>
            </td>
        </tr>
    </table>

</body>
</html>
