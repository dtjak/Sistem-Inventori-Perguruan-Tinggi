@extends('layouts.app')
@section('title', 'Laporan Supplier')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}" class="text-decoration-none">Laporan</a></li>
    <li class="breadcrumb-item active">Supplier</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Laporan Rekanan Supplier</h4>
        <p class="text-muted mb-0">Daftar lengkap mitra supplier inventori perguruan tinggi beserta rating</p>
    </div>
    <div>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
        </a>
    </div>
</div>

<div class="card data-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase font-size-11">
                <tr>
                    <th class="ps-4">No</th>
                    <th>Kode</th>
                    <th>Nama Supplier</th>
                    <th>PIC</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Rating</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                    <tr>
                        <td class="ps-4">{{ $index + 1 }}</td>
                        <td><span class="badge bg-light text-dark font-monospace">{{ $item->kode_supplier }}</span></td>
                        <td class="fw-semibold">{{ $item->nama_supplier }}</td>
                        <td>{{ $item->pic ?: '-' }}</td>
                        <td>{{ $item->telepon ?: '-' }}</td>
                        <td>{{ $item->email ?: '-' }}</td>
                        <td>
                            <div class="text-warning">
                                @php $rating = round($item->rating ?: 0); @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $rating ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                        </td>
                        <td>
                            @if($item->status === 'aktif')
                                <span class="badge bg-success-subtle text-success border px-2 py-1">Aktif</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border px-2 py-1">Non-Aktif</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            Tidak ada data supplier.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
