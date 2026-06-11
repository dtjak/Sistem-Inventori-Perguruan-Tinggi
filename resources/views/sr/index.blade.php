@extends('layouts.app')
@section('title', 'Store Requisition')
@section('breadcrumb')
    <li class="breadcrumb-item active">Store Requisition</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-file-earmark-text text-primary me-2"></i>Store Requisition</h4>
        <p class="text-muted mb-0">Daftar permintaan barang</p>
    </div>
    @can('sr.create')
    <a href="{{ route('sr.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>Buat SR
    </a>
    @endcan
</div>

<!-- Filter -->
<div class="card data-card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label small fw-semibold mb-1">Cari</label>
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Nomor SR atau unit peminjam..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="menunggu_approval" {{ request('status') === 'menunggu_approval' ? 'selected' : '' }}>Menunggu Approval</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="revisi" {{ request('status') === 'revisi' ? 'selected' : '' }}>Revisi</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card data-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nomor SR</th>
                        <th>Tanggal</th>
                        <th>Unit Peminjam</th>
                        <th>Pemohon</th>
                        <th>Status</th>
                        <th>Approved By</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($srs as $sr)
                    <tr>
                        <td class="fw-semibold">
                            <a href="{{ route('sr.show', $sr->id) }}" class="text-decoration-none">{{ $sr->nomor_sr }}</a>
                        </td>
                        <td>{{ $sr->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $sr->unit_peminjam }}</td>
                        <td>{{ $sr->pemohon?->name }}</td>
                        <td>{!! $sr->status_badge !!}</td>
                        <td class="text-muted small">{{ $sr->approvedBy?->name ?? '-' }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('sr.show', $sr->id) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('sr.edit')
                                @if(in_array($sr->status, ['draft', 'revisi']))
                                <a href="{{ route('sr.edit', $sr->id) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif
                                @endcan
                                @can('sr.delete')
                                @if($sr->status === 'draft')
                                <form action="{{ route('sr.destroy', $sr->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-delete" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>Tidak ada Store Requisition
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">Menampilkan {{ $srs->firstItem() ?? 0 }}-{{ $srs->lastItem() ?? 0 }} dari {{ $srs->total() }}</div>
            {{ $srs->links() }}
        </div>
    </div>
</div>
@endsection
