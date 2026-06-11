<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Delivery Requisition</title>
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
        <h2>Laporan Rekapitulasi Delivery Requisition (DR)</h2>
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%">No</th>
                <th style="width: 15%">Nomor DR</th>
                <th style="width: 15%">Nomor SR</th>
                <th style="width: 13%">Tanggal</th>
                <th style="width: 20%">Unit Penerima</th>
                <th style="width: 20%">Petugas Pengirim</th>
                <th class="text-center" style="width: 12%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-monospace fw-bold">{{ $item->nomor_dr }}</td>
                    <td class="font-monospace">{{ $item->storeRequisition->nomor_sr ?? '-' }}</td>
                    <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $item->storeRequisition->unit ?? '-' }}</td>
                    <td>{{ $item->dibuatOleh->name ?? '-' }}</td>
                    <td class="text-center" style="text-transform: uppercase; font-weight: bold;">{{ str_replace('_', ' ', $item->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
