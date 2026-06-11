@extends('layouts.app')
@section('title', 'Laporan Delivery Requisition')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}" class="text-decoration-none">Laporan</a></li>
    <li class="breadcrumb-item active">Delivery Requisition</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Laporan Delivery Requisition (DR)</h4>
        <p class="text-muted mb-0">Rekapitulasi pengiriman dan distribusi barang ke unit-unit kerja</p>
    </div>
    <div>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
        </a>
    </div>
</div>

<div class="card data-card mb-4">
    <div class="card-header bg-white py-3">
        <form action="{{ route('laporan.dr') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="filter" class="form-select" onchange="toggleDateRange(this.value)">
                    <option value="">-- Semua Waktu --</option>
                    <option value="harian" {{ request('filter') === 'harian' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="bulanan" {{ request('filter') === 'bulanan' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tahunan" {{ request('filter') === 'tahunan' ? 'selected' : '' }}>Tahun Ini</option>
                    <option value="rentang" {{ request('filter') === 'rentang' ? 'selected' : '' }}>Rentang Tanggal</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="menunggu_approval" {{ request('status') === 'menunggu_approval' ? 'selected' : '' }}>Menunggu Approval</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div id="date-range-fields" class="col-md-4 d-none">
                <div class="input-group">
                    <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
                    <span class="input-group-text">s/d</span>
                    <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase font-size-11">
                <tr>
                    <th class="ps-4">No</th>
                    <th>Nomor DR</th>
                    <th>Nomor SR</th>
                    <th>Tanggal</th>
                    <th>Unit Pemohon</th>
                    <th>Petugas Gudang</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                    <tr>
                        <td class="ps-4">{{ $index + 1 }}</td>
                        <td><span class="badge bg-light text-dark font-monospace">{{ $item->nomor_dr }}</span></td>
                        <td><span class="font-monospace">{{ $item->storeRequisition->nomor_sr ?? '-' }}</span></td>
                        <td>{{ $item->tanggal->format('d M Y') }}</td>
                        <td>{{ $item->storeRequisition->unit ?? '-' }}</td>
                        <td>{{ $item->dibuatOleh->name ?? '-' }}</td>
                        <td>
                            @if($item->status === 'draft')
                                <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">Draft</span>
                            @elseif($item->status === 'menunggu_approval')
                                <span class="badge bg-warning-subtle text-warning border px-2 py-1">Menunggu Approval</span>
                            @elseif($item->status === 'approved')
                                <span class="badge bg-info-subtle text-info border px-2 py-1">Approved</span>
                            @elseif($item->status === 'selesai')
                                <span class="badge bg-success-subtle text-success border px-2 py-1">Selesai</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border px-2 py-1">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            Tidak ada data pengiriman Delivery Requisition.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleDateRange(val) {
        const fields = document.getElementById('date-range-fields');
        if (val === 'rentang') {
            fields.classList.remove('d-none');
        } else {
            fields.classList.add('d-none');
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        toggleDateRange("{{ request('filter') }}");
    });
</script>
@endsection
