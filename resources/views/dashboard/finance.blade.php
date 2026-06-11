@extends('layouts.app')
@section('title', 'Dashboard Finance')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold mb-1">Dashboard Finance</h4>
        <p class="text-muted mb-0">PO menunggu verifikasi anggaran</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-4">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-gradient-danger text-white"><i class="bi bi-cash-stack"></i></div>
                <div>
                    <div class="stat-label">PO Menunggu Verifikasi</div>
                    <div class="stat-value text-danger">{{ $poMenungguVerifikasi->count() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card data-card">
    <div class="card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-receipt text-danger me-2"></i>Purchase Order Menunggu Verifikasi Anggaran</h6>
    </div>
    <div class="card-body p-0">
        @forelse($poMenungguVerifikasi as $po)
        <div class="p-3 border-bottom">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="fw-semibold">{{ $po->nomor_po }}</div>
                    <div class="text-muted small">Supplier: {{ $po->supplier?->nama_supplier }}</div>
                    <div class="text-muted small">Dibuat oleh: {{ $po->dibuatOleh?->name }}</div>
                    <div class="text-muted small">Tanggal: {{ $po->tanggal->format('d/m/Y') }}</div>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-primary fs-5">Rp {{ number_format($po->total, 0, ',', '.') }}</div>
                    <a href="{{ route('po.show', $po->id) }}" class="btn btn-sm btn-primary mt-2">Verifikasi</a>
                </div>
            </div>
        </div>
        @empty
        <div class="p-4 text-center text-muted">Tidak ada PO yang menunggu verifikasi anggaran</div>
        @endforelse
    </div>
</div>
@endsection
