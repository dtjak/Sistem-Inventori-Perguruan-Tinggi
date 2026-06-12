<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang</title>
    <style>
        * { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; }
        body { margin: 15px; }
        .header { text-align: center; border-bottom: 2px solid #00288E; margin-bottom: 15px; padding-bottom: 10px; }
        .header h2 { color: #00288E; margin: 0; font-size: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #00288E; color: white; padding: 6px 8px; text-align: left; }
        td { padding: 5px 8px; border-bottom: 1px solid #eee; }
        tr:nth-child(even) { background: #f8f9ff; }
        .text-center { text-align: center; }
        .text-danger { color: #e74a3b; }
        .text-warning { color: #f6c23e; }
        .text-success { color: #1cc88a; }
        .footer { margin-top: 15px; border-top: 1px solid #eee; padding-top: 8px; color: #999; text-align: center; font-size: 9px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN DATA BARANG</h2>
        <div>Inventori Perguruan Tinggi | Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Satuan</th>
                <th class="text-center">Stok Min</th>
                <th class="text-center">Stok Saat Ini</th>
                <th>Lokasi</th>
                <th>Status Stok</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $barang)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $barang->kode_barang }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->kategori }}</td>
                <td>{{ $barang->satuan }}</td>
                <td class="text-center">{{ $barang->stok_minimum }}</td>
                <td class="text-center {{ $barang->stok_saat_ini === 0 ? 'text-danger' : ($barang->stok_saat_ini <= $barang->stok_minimum ? 'text-warning' : 'text-success') }}">
                    <strong>{{ $barang->stok_saat_ini }}</strong>
                </td>
                <td>{{ $barang->lokasi_gudang ?? '-' }}</td>
                <td>{{ $barang->status_stok }}</td>
                <td>{{ ucfirst($barang->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Total: {{ count($data) }} barang | Sistem Informasi Inventori Perguruan Tinggi
    </div>
</body>
</html>
