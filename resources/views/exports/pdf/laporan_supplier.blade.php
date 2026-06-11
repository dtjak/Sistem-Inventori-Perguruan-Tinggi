<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekanan Supplier</title>
    <style>
        * { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9px; color: #333; }
        body { margin: 10px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px double #333; padding-bottom: 8px; }
        .header h2 { margin: 0; font-size: 14px; font-weight: bold; text-transform: uppercase; color: #111; }
        .header p { margin: 3px 0 0 0; font-size: 9px; color: #666; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .items-table th { background-color: #f5f5f5; border: 1px solid #ddd; padding: 6px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 8px; }
        .items-table td { border: 1px solid #ddd; padding: 6px; }
        .items-table tr:nth-child(even) { background-color: #fafafa; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        .font-monospace { font-family: 'Courier New', Courier, monospace; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Rekanan Supplier Perguruan Tinggi</h2>
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%">No</th>
                <th style="width: 12%">Kode</th>
                <th style="width: 20%">Nama Supplier</th>
                <th style="width: 15%">PIC</th>
                <th style="width: 15%">Telepon</th>
                <th style="width: 18%">Email</th>
                <th class="text-center" style="width: 7%">Rating</th>
                <th class="text-center" style="width: 8%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-monospace fw-bold">{{ $item->kode_supplier }}</td>
                    <td>{{ $item->nama_supplier }}</td>
                    <td>{{ $item->pic ?: '-' }}</td>
                    <td>{{ $item->telepon ?: '-' }}</td>
                    <td>{{ $item->email ?: '-' }}</td>
                    <td class="text-center">{{ number_format($item->rating ?: 0, 1) }} / 5.0</td>
                    <td class="text-center" style="text-transform: uppercase;">{{ $item->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
