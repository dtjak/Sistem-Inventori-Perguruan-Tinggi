@extends('layouts.app')
@section('title', 'Purchase Requisition')
@section('breadcrumb')
    <li class="breadcrumb-item active">Purchase Requisition</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Purchase Requisition (PR)</h4>
        <p class="text-muted mb-0">Kelola pengajuan pembelian barang inventori baru</p>
    </div>
    <div>
        @can('pr.create')
            <a href="{{ route('pr.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Buat PR Baru
            </a>
        @endcan
    </div>
</div>

<div class="card data-card">
    <div class="card-header bg-white py-3">
        <form action="{{ route('pr.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" 
                           placeholder="Cari nomor PR..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="menunggu_approval" {{ request('status') === 'menunggu_approval' ? 'selected' : '' }}>Menunggu Approval</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
            @if(request('search') || request('status'))
                <div class="col-md-2">
                    <a href="{{ route('pr.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase font-size-11">
                <tr>
                    <th class="ps-4" style="width: 5%">No</th>
                    <th style="width: 15%">Nomor PR</th>
                    <th style="width: 15%">Tanggal</th>
                    <th style="width: 25%">Alasan Pengajuan</th>
                    <th style="width: 15%">Total Estimasi</th>
                    <th style="width: 13%">Status</th>
                    <th class="text-end pe-4" style="width: 12%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prs as $index => $pr)
                    <tr>
                        <td class="ps-4">{{ $prs->firstItem() + $index }}</td>
                        <td><span class="badge bg-light text-dark font-monospace fw-semibold fs-6">{{ $pr->nomor_pr }}</span></td>
                        <td>{{ $pr->tanggal->format('d M Y') }}</td>
                        <td>
                            <div class="text-truncate" style="max-width: 220px;" title="{{ $pr->alasan }}">
                                {{ $pr->alasan }}
                            </div>
                            <div class="text-muted small">Oleh: {{ $pr->dibuatOleh->name ?? '-' }}</div>
                        </td>
                        <td class="fw-semibold">Rp {{ number_format($pr->total_estimasi, 0, ',', '.') }}</td>
                        <td>
                            @if($pr->status === 'draft')
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Draft</span>
                            @elseif($pr->status === 'menunggu_approval')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">Menunggu Approval</span>
                            @elseif($pr->status === 'approved')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Disetujui</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Ditolak</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('pr.show', $pr->id) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(in_array($pr->status, ['draft', 'revisi']))
                                    @can('pr.edit')
                                        <a href="{{ route('pr.edit', $pr->id) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endcan
                                    @can('pr.delete')
                                        <button type="button" class="btn btn-outline-danger btn-delete" 
                                                data-id="{{ $pr->id }}" 
                                                data-nomor="{{ $pr->nomor_pr }}" 
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                @endif
                            </div>
                            @if($pr->status === 'draft')
                                @can('pr.delete')
                                    <form id="delete-form-{{ $pr->id }}" action="{{ route('pr.destroy', $pr->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            Tidak ada data PR ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($prs->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $prs->links() }}
        </div>
    @endif
</div>

@can('pr.delete')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nomor = this.getAttribute('data-nomor');
                
                Swal.fire({
                    title: 'Hapus PR?',
                    text: `Apakah Anda yakin ingin menghapus PR "${nomor}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${id}`).submit();
                    }
                });
            });
        });
    });
</script>
@endcan
@endsection
