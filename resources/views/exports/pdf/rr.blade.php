<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Receiving Report - {{ $rr->nomor_rr }}</title>
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
        <h2>Receiving Report (RR)</h2>
        <p>Sistem Informasi Inventori Perguruan Tinggi</p>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 18%;" class="fw-bold">Nomor RR</td>
            <td style="width: 32%;">: <span class="font-monospace fw-bold">{{ $rr->nomor_rr }}</span></td>
            <td style="width: 18%;" class="fw-bold">Supplier Rekanan</td>
            <td style="width: 32%;">: <span class="fw-bold">{{ $rr->purchaseOrder->supplier->nama_supplier ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="fw-bold">Tanggal Terima</td>
            <td>: {{ $rr->tanggal_terima->format('d/m/Y') }}</td>
            <td class="fw-bold">Rujukan PO</td>
            <td>: <span class="font-monospace">{{ $rr->purchaseOrder->nomor_po ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="fw-bold">Petugas Gudang</td>
            <td>: {{ $rr->penerima->name ?? '-' }}</td>
            <td class="fw-bold">Status</td>
            <td>: <span style="text-transform: uppercase; font-weight: bold;">{{ $rr->status }}</span></td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%">No</th>
                <th style="width: 15%">Kode Barang</th>
                <th style="width: 40%">Nama Barang</th>
                <th class="text-center" style="width: 13%">Qty PO</th>
                <th class="text-center" style="width: 13%">Qty Terima</th>
                <th class="text-center" style="width: 14%">Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rr->details as $index => $detail)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-monospace">{{ $detail->barang->kode_barang }}</td>
                    <td>{{ $detail->barang->nama_barang }}</td>
                    <td class="text-center">{{ $detail->qty_dipesan }} {{ $detail->barang->satuan }}</td>
                    <td class="text-center fw-bold">{{ $detail->qty_diterima }} {{ $detail->barang->satuan }}</td>
                    <td class="text-center" style="text-transform: uppercase;">{{ $detail->kondisi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($rr->catatan)
        <div class="notes">
            <div class="notes-title">Catatan Penerimaan / No. Surat Jalan</div>
            <div>{{ $rr->catatan }}</div>
        </div>
    @endif

    <table class="footer-table">
        <tr>
            <td>
                <p>Pengirim (Supplier)</p>
                <div class="signature-space"></div>
                <p class="fw-bold">( _______________________ )</p>
                <p>Kurir / Ekspedisi</p>
            </td>
            <td>
                <p>Diterima Oleh,</p>
                <div class="signature-space"></div>
                <p class="fw-bold">( {{ $rr->penerima->name ?? '___________________' }} )</p>
                <p>Staff Gudang</p>
            </td>
            <td>
                <p>Mengetahui,</p>
                <div class="signature-space"></div>
                <p class="fw-bold">( {{ $rr->approvedBy->name ?? '___________________' }} )</p>
                <p>Head Inventori</p>
            </td>
        </tr>
    </table>

</body>
</html>
