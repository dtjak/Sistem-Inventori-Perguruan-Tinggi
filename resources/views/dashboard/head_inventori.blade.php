@extends('layouts.app')
@section('title', 'Dashboard - Head Inventori')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold mb-1">Dashboard Head Inventori</h4>
        <p class="text-muted mb-0">Menunggu persetujuan Anda</p>
    </div>
</div>

<div class="row g-4">
    <!-- DR Menunggu -->
    <div class="col-lg-6">
        <div class="card data-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-box-arrow-right text-primary me-2"></i>DR Menunggu Approval</h6>
                <span class="badge bg-primary">{{ $drMenunggu->count() }}</span>
            </div>
            <div class="card-body p-0">
                @forelse($drMenunggu as $dr)
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold" style="font-size:0.85rem;">{{ $dr->nomor_dr }}</div>
                        <div class="text-muted small">SR: {{ $dr->storeRequisition?->nomor_sr }}</div>
                        <div class="text-muted small">{{ $dr->tanggal->format('d/m/Y') }}</div>
                    </div>
                    <a href="{{ route('dr.show', $dr->id) }}" class="btn btn-sm btn-primary">Review</a>
                </div>
                @empty
                <div class="p-3 text-muted text-center small">Tidak ada DR yang menunggu</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- PR Menunggu -->
    <div class="col-lg-6">
        <div class="card data-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-cart-plus text-success me-2"></i>PR Menunggu Approval</h6>
                <span class="badge bg-success">{{ $prMenunggu->count() }}</span>
            </div>
            <div class="card-body p-0">
                @forelse($prMenunggu as $pr)
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold" style="font-size:0.85rem;">{{ $pr->nomor_pr }}</div>
                        <div class="text-muted small">{{ $pr->dibuatOleh?->name }}</div>
                        <div class="text-muted small">{{ $pr->tanggal->format('d/m/Y') }}</div>
                    </div>
                    <a href="{{ route('pr.show', $pr->id) }}" class="btn btn-sm btn-success">Review</a>
                </div>
                @empty
                <div class="p-3 text-muted text-center small">Tidak ada PR yang menunggu</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- RR Menunggu -->
    <div class="col-lg-6">
        <div class="card data-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-box-arrow-in-down text-info me-2"></i>RR Menunggu Approval</h6>
                <span class="badge bg-info">{{ $rrMenunggu->count() }}</span>
            </div>
            <div class="card-body p-0">
                @forelse($rrMenunggu as $rr)
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold" style="font-size:0.85rem;">{{ $rr->nomor_rr }}</div>
                        <div class="text-muted small">Penerima: {{ $rr->penerima?->name }}</div>
                        <div class="text-muted small">{{ $rr->tanggal_terima->format('d/m/Y') }}</div>
                    </div>
                    <a href="{{ route('rr.show', $rr->id) }}" class="btn btn-sm btn-info">Review</a>
                </div>
                @empty
                <div class="p-3 text-muted text-center small">Tidak ada RR yang menunggu</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Retur Menunggu -->
    <div class="col-lg-6">
        <div class="card data-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-arrow-return-left text-danger me-2"></i>Retur Menunggu Approval</h6>
                <span class="badge bg-danger">{{ $returMenunggu->count() }}</span>
            </div>
            <div class="card-body p-0">
                @forelse($returMenunggu as $retur)
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold" style="font-size:0.85rem;">{{ $retur->nomor_retur }}</div>
                        <div class="text-muted small">{{ $retur->dibuatOleh?->name }}</div>
                        <div class="text-muted small">{{ $retur->tanggal->format('d/m/Y') }}</div>
                    </div>
                    <a href="{{ route('retur.show', $retur->id) }}" class="btn btn-sm btn-danger">Review</a>
                </div>
                @empty
                <div class="p-3 text-muted text-center small">Tidak ada Retur yang menunggu</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
