@extends('layouts.app')
@section('title', 'Master Supplier')
@section('breadcrumb')
    <li class="breadcrumb-item active">Master Supplier</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Master Supplier</h4>
        <p class="text-muted mb-0">Kelola master data rekanan/supplier inventori</p>
    </div>
    <div>
        @can('supplier.create')
            <a href="{{ route('master.supplier.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Tambah Supplier
            </a>
        @endcan
    </div>
</div>

<div class="card data-card">
    <div class="card-header bg-white py-3">
        <form action="{{ route('master.supplier.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" 
                           placeholder="Cari nama, kode, atau PIC..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
            @if(request('search') || request('status'))
                <div class="col-md-2">
                    <a href="{{ route('master.supplier.index') }}" class="btn btn-light w-100">
                        Reset
                    </a>
                </div>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase font-size-11">
                <tr>
                    <th class="ps-4" style="width: 5%">No</th>
                    <th style="width: 12%">Kode</th>
                    <th style="width: 20%">Nama Supplier</th>
                    <th style="width: 15%">PIC</th>
                    <th style="width: 15%">Telepon</th>
                    <th style="width: 13%">Status</th>
                    <th style="width: 10%">Rating</th>
                    <th class="text-end pe-4" style="width: 10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $index => $supplier)
                    <tr>
                        <td class="ps-4">{{ $suppliers->firstItem() + $index }}</td>
                        <td><span class="badge bg-light text-dark font-monospace">{{ $supplier->kode_supplier }}</span></td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $supplier->nama_supplier }}</div>
                            <div class="text-muted small">{{ $supplier->email ?: '-' }}</div>
                        </td>
                        <td>{{ $supplier->pic ?: '-' }}</td>
                        <td>{{ $supplier->telepon ?: '-' }}</td>
                        <td>
                            @if($supplier->status === 'aktif')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Aktif</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Non-Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="text-warning">
                                @php $rating = round($supplier->rating ?: 0); @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $rating ? '-fill' : '' }}"></i>
                                @endfor
                                <span class="text-muted small ms-1">({{ number_format($supplier->rating ?: 0, 1) }})</span>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('master.supplier.show', $supplier->id) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('supplier.edit')
                                    <a href="{{ route('master.supplier.edit', $supplier->id) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endcan
                                @can('supplier.delete')
                                    <button type="button" class="btn btn-outline-danger btn-delete" 
                                            data-id="{{ $supplier->id }}" 
                                            data-nama="{{ $supplier->nama_supplier }}" 
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endcan
                            </div>
                            @can('supplier.delete')
                                <form id="delete-form-{{ $supplier->id }}" action="{{ route('master.supplier.destroy', $supplier->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            Tidak ada data supplier ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($suppliers->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $suppliers->links() }}
        </div>
    @endif
</div>

@can('supplier.delete')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                
                Swal.fire({
                    title: 'Hapus Supplier?',
                    text: `Apakah Anda yakin ingin menghapus supplier "${nama}"?`,
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
