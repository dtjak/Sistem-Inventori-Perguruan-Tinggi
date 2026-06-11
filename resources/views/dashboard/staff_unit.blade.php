@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold mb-1">Dashboard Saya</h4>
        <p class="text-muted mb-0">{{ auth()->user()->name }} &bull; {{ auth()->user()->unit }}</p>
    </div>
    @can('sr.create')
    <div class="col-auto">
        <a href="{{ route('sr.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Buat Store Requisition
        </a>
    </div>
    @endcan
</div>

<div class="card data-card">
    <div class="card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-file-earmark-text text-primary me-2"></i>Store Requisition Saya</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>Nomor SR</th>
                        <th>Tanggal</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($srSaya as $sr)
                    <tr>
                        <td class="fw-semibold">{{ $sr->nomor_sr }}</td>
                        <td>{{ $sr->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $sr->unit_peminjam }}</td>
                        <td>{!! $sr->status_badge !!}</td>
                        <td>
                            <a href="{{ route('sr.show', $sr->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
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
