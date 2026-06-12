@extends('layouts.app')
@section('title', 'Delivery Requisition')
@section('breadcrumb')
    <li class="breadcrumb-item active">Delivery Requisition</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Delivery Requisition (DR)</h4>
        <p class="text-muted mb-0">Kelola distribusi barang berdasarkan SR disetujui</p>
    </div>
</div>

<div class="card data-card">
    <div class="card-header bg-white py-3">
        <form action="{{ route('dr.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" 
                           placeholder="Cari nomor DR..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="menunggu_approval" {{ request('status') === 'menunggu_approval' ? 'selected' : '' }}>Menunggu Approval</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved (Distribusi)</option>
                    <option value="dikirim" {{ request('status') === 'dikirim' ? 'selected' : '' }}>Sedang Dikirim</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
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
                    <a href="{{ route('dr.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase font-size-11">
                <tr>
                    <th class="ps-4" style="width: 5%">No</th>
                    <th style="width: 15%">Nomor DR</th>
                    <th style="width: 15%">Nomor SR</th>
                    <th style="width: 15%">Tanggal</th>
                    <th style="width: 20%">Dibuat Oleh</th>
                    <th style="width: 15%">Status</th>
                    <th class="text-end pe-4" style="width: 15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drs as $index => $dr)
                    <tr>
                        <td class="ps-4">{{ $drs->firstItem() + $index }}</td>
                        <td><span class="badge bg-light text-dark font-monospace fw-semibold fs-6">{{ $dr->nomor_dr }}</span></td>
                        <td>
                            @if($dr->storeRequisition)
                                <a href="{{ route('sr.show', $dr->store_requisition_id) }}" class="text-decoration-none font-monospace">{{ $dr->storeRequisition->nomor_sr }}</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $dr->tanggal->format('d M Y') }}</td>
                        <td>{{ $dr->dibuatOleh->name ?? '-' }}</td>
                        <td>
                            {!! $dr->status_badge !!}
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('dr.show', $dr->id) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(in_array($dr->status, ['draft', 'revisi']))
                                    @can('dr.edit')
                                        <a href="{{ route('dr.edit', $dr->id) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endcan
                                    @can('dr.delete')
                                        <button type="button" class="btn btn-outline-danger btn-delete" 
                                                data-id="{{ $dr->id }}" 
                                                data-nomor="{{ $dr->nomor_dr }}" 
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                @endif
                            </div>
                            @if($dr->status === 'draft')
                                @can('dr.delete')
                                    <form id="delete-form-{{ $dr->id }}" action="{{ route('dr.destroy', $dr->id) }}" method="POST" class="d-none">
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
                            Tidak ada data DR ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($drs->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $drs->links() }}
        </div>
    @endif
</div>

@can('dr.delete')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nomor = this.getAttribute('data-nomor');
                
                Swal.fire({
                    title: 'Hapus DR?',
                    text: `Apakah Anda yakin ingin menghapus DR "${nomor}"?`,
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
