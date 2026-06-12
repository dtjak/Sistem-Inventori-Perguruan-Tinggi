<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Store Requisition - {{ $sr->nomor_sr }}</title>
    <style>
        * { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; }
        body { margin: 20px; color: #333; }
        .header { border-bottom: 3px solid #00288E; padding-bottom: 10px; margin-bottom: 20px; }
        .company-name { font-size: 18px; font-weight: bold; color: #00288E; }
        .doc-title { font-size: 14px; font-weight: bold; margin-top: 5px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 4px 8px; }
        .info-table .label { font-weight: bold; width: 30%; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #00288E; color: white; padding: 8px; text-align: left; }
        td { padding: 6px 8px; border-bottom: 1px solid #eee; }
        tr:nth-child(even) { background: #f9f9ff; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .badge-warning { background: #f6c23e; color: #333; }
        .badge-success { background: #1cc88a; color: white; }
        .badge-danger { background: #e74a3b; color: white; }
        .badge-secondary { background: #6b7280; color: white; }
        .badge-info { background: #36b9cc; color: white; }
        .signature-area { margin-top: 40px; }
        .signature-box { display: inline-block; width: 200px; text-align: center; }
        .signature-line { border-top: 1px solid #333; margin-top: 50px; padding-top: 5px; }
        .footer { border-top: 1px solid #eee; margin-top: 20px; padding-top: 10px; color: #999; font-size: 9px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">INVENTORI PERGURUAN TINGGI</div>
        <div class="doc-title">STORE REQUISITION (SR)</div>
        <div>Nomor: <strong>{{ $sr->nomor_sr }}</strong></div>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Tanggal</td>
            <td>: {{ $sr->tanggal->format('d F Y') }}</td>
            <td class="label">Unit Peminjam</td>
            <td>: {{ $sr->unit_peminjam }}</td>
        </tr>
        <tr>
            <td class="label">Pemohon</td>
            <td>: {{ $sr->pemohon?->name }}</td>
            <td class="label">Status</td>
            <td>: {{ strtoupper($sr->status) }}</td>
        </tr>
        @if($sr->approvedBy)
        <tr>
            <td class="label">Disetujui Oleh</td>
            <td>: {{ $sr->approvedBy->name }}</td>
            <td class="label">Tanggal Disetujui</td>
            <td>: {{ $sr->approved_at?->format('d/m/Y H:i') }}</td>
        </tr>
        @endif
        @if($sr->catatan)
        <tr>
            <td class="label">Catatan</td>
            <td colspan="3">: {{ $sr->catatan }}</td>
        </tr>
        @endif
    </table>

    <h4 style="color:#00288E; border-bottom:1px solid #00288E; padding-bottom:5px;">Detail Permohonan (Barang / Aset)</h4>

    <table>
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th style="width:10%;">Tipe</th>
                <th style="width:15%;">Kode</th>
                <th>Nama Item</th>
                <th style="width:10%; text-align:center;">Qty</th>
                <th style="width:15%;">Satuan / Lokasi</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sr->details as $i => $detail)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $detail->aset_id ? 'ASET' : 'BARANG' }}</td>
                <td>{{ $detail->aset_id ? $detail->aset?->kode_aset : $detail->barang?->kode_barang }}</td>
                <td>{{ $detail->aset_id ? $detail->aset?->nama_aset : $detail->barang?->nama_barang }}</td>
                <td style="text-align:center;">{{ $detail->qty }}</td>
                <td>{{ $detail->aset_id ? ($detail->aset?->lokasi ?? '-') : ($detail->barang?->satuan ?? '-') }}</td>
                <td>{{ $detail->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-area">
        <table style="border:none;">
            <tr>
                <td style="width:33%; text-align:center; border:none;">
                    <div>Pemohon</div>
                    <br><br><br><br>
                    <div style="border-top:1px solid #333; padding-top:5px;">
                        {{ $sr->pemohon?->name }}<br>
                        <small>{{ $sr->pemohon?->nip }}</small>
                    </div>
                </td>
                <td style="width:33%; text-align:center; border:none;">
                    <div>Head Unit Peminjam</div>
                    <br><br><br><br>
                    <div style="border-top:1px solid #333; padding-top:5px;">
                        {{ $sr->approvedBy?->name ?? '............................' }}<br>
                        <small>{{ $sr->approvedBy?->nip ?? '&nbsp;' }}</small>
                    </div>
                </td>
                <td style="width:33%; text-align:center; border:none;">
                    <div>Staff Inventori</div>
                    <br><br><br><br>
                    <div style="border-top:1px solid #333; padding-top:5px;">...............................
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }} | Sistem Informasi Inventori Perguruan Tinggi
    </div>
</body>
</html>
