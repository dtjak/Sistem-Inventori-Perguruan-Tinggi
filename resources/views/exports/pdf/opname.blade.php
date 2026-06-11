<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Stock Opname - {{ $opname->nomor_opname }}</title>
    <style>
        * { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #333; }
        body { margin: 15px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 2px double #333; padding-bottom: 8px; }
        .header h2 { margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase; color: #111; }
        .header p { margin: 3px 0 0 0; font-size: 10px; color: #666; }
        .meta-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .meta-table td { padding: 4px 0; vertical-align: top; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .items-table th { background-color: #f5f5f5; border: 1px solid #ddd; padding: 7px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 8px; }
        .items-table td { border: 1px solid #ddd; padding: 7px; }
        .items-table tr:nth-child(even) { background-color: #fafafa; }
        .notes { border: 1px solid #ddd; padding: 10px; background-color: #fcfcfc; margin-bottom: 30px; border-radius: 4px; }
        .notes-title { font-weight: bold; margin-bottom: 4px; font-size: 9px; text-transform: uppercase; color: #555; }
        .footer-table { width: 100%; margin-top: 40px; border-collapse: collapse; page-break-inside: avoid; }
        .footer-table td { text-align: center; width: 50%; }
        .signature-space { height: 60px; }
        .font-monospace { font-family: 'Courier New', Courier, monospace; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Stock Opname Report</h2>
        <p>Sistem Informasi Inventori Perguruan Tinggi</p>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 15%;" class="fw-bold">Nomor Opname</td>
            <td style="width: 35%;">: <span class="font-monospace fw-bold">{{ $opname->nomor_opname }}</span></td>
            <td style="width: 15%;" class="fw-bold">Pelaksana/Petugas</td>
            <td style="width: 35%;">: {{ $opname->petugas->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="fw-bold">Tanggal Pelaksanaan</td>
            <td>: {{ $opname->tanggal->format('d/m/Y') }}</td>
            <td class="fw-bold">Status</td>
            <td>: <span style="text-transform: uppercase; font-weight: bold;">{{ $opname->status }}</span></td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%">No</th>
                <th style="width: 15%">Kode Barang</th>
                <th style="width: 35%">Nama Barang</th>
                <th class="text-center" style="width: 12%">Stok Sistem</th>
                <th class="text-center" style="width: 12%">Stok Fisik</th>
                <th class="text-center" style="width: 11%">Selisih</th>
                <th style="width: 10%">Temuan/Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($opname->details as $index => $detail)
                @php $selisih = $detail->stok_fisik - $detail->stok_sistem; @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-monospace">{{ $detail->barang->kode_barang }}</td>
                    <td>{{ $detail->barang->nama_barang }}</td>
                    <td class="text-center">{{ $detail->stok_sistem }} {{ $detail->barang->satuan }}</td>
                    <td class="text-center fw-bold">{{ $detail->stok_fisik }} {{ $detail->barang->satuan }}</td>
                    <td class="text-center fw-bold" style="color: {{ $selisih == 0 ? '#555' : ($selisih > 0 ? 'green' : 'red') }}">
                        {{ $selisih > 0 ? '+' : '' }}{{ $selisih }} {{ $detail->barang->satuan }}
                    </td>
                    <td>{{ $detail->keterangan ?: '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($opname->catatan)
        <div class="notes">
            <div class="notes-title">Catatan Stock Opname</div>
            <div>{{ $opname->catatan }}</div>
        </div>
    @endif

    <table class="footer-table">
        <tr>
            <td>
                <p>Petugas Gudang / Pelaksana</p>
                <div class="signature-space"></div>
                <p class="fw-bold">( {{ $opname->petugas->name ?? '___________________' }} )</p>
                <p>Staff Inventori</p>
            </td>
            <td>
                <p>Mengetahui,</p>
                <div class="signature-space"></div>
                <p class="fw-bold">( _______________________ )</p>
                <p>Head Inventori</p>
            </td>
        </tr>
    </table>

</body>
</html>
