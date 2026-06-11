@extends('layouts.app')
@section('title', 'Receiving Report')
@section('breadcrumb')
    <li class="breadcrumb-item active">Receiving Report</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Receiving Report (RR)</h4>
        <p class="text-muted mb-0">Kelola penerimaan barang datang berdasarkan Purchase Order (PO)</p>
    </div>
</div>

<div class="card data-card">
    <div class="card-header bg-white py-3">
        <form action="{{ route('rr.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" 
                           placeholder="Cari nomor RR..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="menunggu_approval" {{ request('status') === 'menunggu_approval' ? 'selected' : '' }}>Menunggu Approval</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui (Stok Masuk)</option>
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
                    <a href="{{ route('rr.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase font-size-11">
                <tr>
                    <th class="ps-4" style="width: 5%">No</th>
                    <th style="width: 15%">Nomor RR</th>
                    <th style="width: 15%">Nomor PO</th>
                    <th style="width: 20%">Supplier</th>
                    <th style="width: 15%">Tanggal Terima</th>
                    <th style="width: 13%">Status</th>
                    <th class="text-end pe-4" style="width: 17%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rrs as $index => $rr)
                    <tr>
                        <td class="ps-4">{{ $rrs->firstItem() + $index }}</td>
                        <td><span class="badge bg-light text-dark font-monospace fw-semibold fs-6">{{ $rr->nomor_rr }}</span></td>
                        <td>
                            @if($rr->purchaseOrder)
                                <a href="{{ route('po.show', $rr->purchase_order_id) }}" class="text-decoration-none font-monospace">{{ $rr->purchaseOrder->nomor_po }}</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $rr->purchaseOrder->supplier->nama_supplier ?? '-' }}</td>
                        <td>{{ $rr->tanggal_terima->format('d M Y') }}</td>
                        <td>
                            @if($rr->status === 'draft')
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Draft</span>
                            @elseif($rr->status === 'menunggu_approval')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">Menunggu Approval</span>
                            @elseif($rr->status === 'approved')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Disetujui</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Ditolak</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('rr.show', $rr->id) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($rr->status === 'draft')
                                    @can('rr.edit')
                                        <a href="{{ route('rr.edit', $rr->id) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endcan
                                    @can('rr.delete')
                                        <button type="button" class="btn btn-outline-danger btn-delete" 
                                                data-id="{{ $rr->id }}" 
                                                data-nomor="{{ $rr->nomor_rr }}" 
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                @endif
                            </div>
                            @if($rr->status === 'draft')
                                @can('rr.delete')
                                    <form id="delete-form-{{ $rr->id }}" action="{{ route('rr.destroy', $rr->id) }}" method="POST" class="d-none">
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
                            Tidak ada data RR ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($rrs->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $rrs->links() }}
        </div>
    @endif
</div>

@can('rr.delete')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nomor = this.getAttribute('data-nomor');
                
                Swal.fire({
                    title: 'Hapus RR?',
                    text: `Apakah Anda yakin ingin menghapus Receiving Report "${nomor}"?`,
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
