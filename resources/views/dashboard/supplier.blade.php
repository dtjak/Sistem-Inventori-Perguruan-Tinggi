@extends('layouts.app')
@section('title', 'Dashboard Supplier')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold mb-1">Dashboard Supplier</h4>
        <p class="text-muted mb-0">Purchase Order dari Inventori PT</p>
    </div>
</div>

<div class="card data-card">
    <div class="card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-receipt text-primary me-2"></i>Purchase Order Aktif</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>Nomor PO</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Kirim Paling Lambat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($poList as $po)
                    <tr>
                        <td class="fw-semibold">{{ $po->nomor_po }}</td>
                        <td>{{ $po->tanggal->format('d/m/Y') }}</td>
                        <td>Rp {{ number_format($po->total, 0, ',', '.') }}</td>
                        <td>{{ $po->tanggal_kirim?->format('d/m/Y') ?? '-' }}</td>
                        <td>
                            <a href="{{ route('po.show', $po->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
