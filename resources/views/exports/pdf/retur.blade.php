<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Retur Barang - {{ $retur->nomor_retur }}</title>
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
        <h2>Retur Barang</h2>
        <p>Sistem Informasi Inventori Perguruan Tinggi</p>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 18%;" class="fw-bold">Nomor Retur</td>
            <td style="width: 32%;">: <span class="font-monospace fw-bold">{{ $retur->nomor_retur }}</span></td>
            <td style="width: 18%;" class="fw-bold">Supplier Tujuan</td>
            <td style="width: 32%;">: <span class="fw-bold">{{ $retur->supplier->nama_supplier ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="fw-bold">Tanggal Retur</td>
            <td>: {{ $retur->tanggal->format('d/m/Y') }}</td>
            <td class="fw-bold">Rujukan RR</td>
            <td>: <span class="font-monospace">{{ $retur->receivingReport->nomor_rr ?? '-' }}</span></td>
        </tr>
        <tr>
            <td class="fw-bold">Petugas Gudang</td>
            <td>: {{ $retur->dibuatOleh->name ?? '-' }}</td>
            <td class="fw-bold">Status</td>
            <td>: <span style="text-transform: uppercase; font-weight: bold;">{{ $retur->status }}</span></td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 8%">No</th>
                <th style="width: 22%">Kode Barang</th>
                <th style="width: 48%">Nama Barang</th>
                <th class="text-center" style="width: 22%">Quantity Retur</th>
            </tr>
        </thead>
        <tbody>
            @foreach($retur->details as $index => $detail)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-monospace">{{ $detail->barang->kode_barang }}</td>
                    <td>{{ $detail->barang->nama_barang }}</td>
                    <td class="text-center fw-bold text-danger">{{ $detail->qty }} {{ $detail->barang->satuan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($retur->alasan)
        <div class="notes">
            <div class="notes-title">Alasan Pengembalian (Retur)</div>
            <div>{{ $retur->alasan }}</div>
        </div>
    @endif

    @if($retur->catatan)
        <div class="notes">
            <div class="notes-title">Catatan Tambahan</div>
            <div>{{ $retur->catatan }}</div>
        </div>
    @endif

    <table class="footer-table">
        <tr>
            <td>
                <p>Petugas Gudang</p>
                <div class="signature-space"></div>
                <p class="fw-bold">( {{ $retur->dibuatOleh->name ?? '___________________' }} )</p>
                <p>Staff Inventori</p>
            </td>
            <td>
                <p>Mengetahui,</p>
                <div class="signature-space"></div>
                <p class="fw-bold">( {{ $retur->approvedBy->name ?? '___________________' }} )</p>
                <p>Head Inventori</p>
            </td>
            <td>
                <p>Penerima (Supplier)</p>
                <div class="signature-space"></div>
                <p class="fw-bold">( _______________________ )</p>
                <p>Kurir / Supplier PIC</p>
            </td>
        </tr>
    </table>

</body>
</html>
