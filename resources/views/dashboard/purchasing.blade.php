@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold mb-1">Dashboard Purchasing</h4>
        <p class="text-muted mb-0">Selamat datang, {{ auth()->user()->name }}</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-gradient-success text-white">
                    <i class="bi bi-cart-plus"></i>
                </div>
                <div>
                    <div class="stat-label">PR Siap Dibuatkan PO</div>
                    <div class="stat-value text-success">{{ $prApproved->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-gradient-warning text-white">
                    <i class="bi bi-receipt"></i>
                </div>
                <div>
                    <div class="stat-label">PO Menunggu Approval</div>
                    <div class="stat-value text-warning">{{ $poMenunggu->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-gradient-primary text-white">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
                <div>
                    <div class="stat-label">Total PO</div>
                    <div class="stat-value text-primary">{{ $totalPO }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card data-card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-cart-plus text-success me-2"></i>PR Approved (Belum ada PO)</h6>
            </div>
            <div class="card-body p-0">
                @forelse($prApproved as $pr)
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold" style="font-size:0.85rem;">{{ $pr->nomor_pr }}</div>
                        <div class="text-muted small">{{ $pr->dibuatOleh?->name }} &bull; {{ $pr->tanggal->format('d/m/Y') }}</div>
                    </div>
                    <a href="{{ route('po.create') }}?pr_id={{ $pr->id }}" class="btn btn-sm btn-success">Buat PO</a>
                </div>
                @empty
                <div class="p-3 text-muted text-center small">Tidak ada PR yang perlu dibuatkan PO</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card data-card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-hourglass-split text-warning me-2"></i>PO Menunggu Approval</h6>
            </div>
            <div class="card-body p-0">
                @forelse($poMenunggu as $po)
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold" style="font-size:0.85rem;">{{ $po->nomor_po }}</div>
                        <div class="text-muted small">{!! $po->status_badge !!}</div>
                    </div>
                    <a href="{{ route('po.show', $po->id) }}" class="btn btn-sm btn-warning">Review</a>
                </div>
                @empty
                <div class="p-3 text-muted text-center small">Tidak ada PO yang menunggu</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
