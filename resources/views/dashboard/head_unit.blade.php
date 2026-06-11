@extends('layouts.app')
@section('title', 'Dashboard Head Unit')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold mb-1">Dashboard Head Unit</h4>
        <p class="text-muted mb-0">SR menunggu persetujuan Anda</p>
    </div>
</div>

<div class="card data-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="bi bi-file-earmark-check text-primary me-2"></i>SR Menunggu Approval</h6>
        <span class="badge bg-primary">{{ $srMenunggu->count() }}</span>
    </div>
    <div class="card-body p-0">
        @forelse($srMenunggu as $sr)
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
            <div>
                <div class="fw-semibold">{{ $sr->nomor_sr }}</div>
                <div class="text-muted small">{{ $sr->pemohon?->name }} &bull; {{ $sr->unit_peminjam }}</div>
                <div class="text-muted small">{{ $sr->tanggal->format('d/m/Y') }}</div>
            </div>
            <a href="{{ route('sr.show', $sr->id) }}" class="btn btn-sm btn-primary">Review & Approve</a>
        </div>
        @empty
        <div class="p-4 text-center text-muted">Tidak ada SR yang menunggu approval</div>
        @endforelse
    </div>
</div>
@endsection
