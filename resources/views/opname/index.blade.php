@extends('layouts.app')
@section('title', 'Stock Opname')
@section('breadcrumb')
    <li class="breadcrumb-item active">Stock Opname</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Stock Opname</h4>
        <p class="text-muted mb-0">Kelola proses pencocokan stok fisik gudang dengan sistem</p>
    </div>
    <div>
        @can('opname.create')
            <a href="{{ route('opname.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Mulai Stock Opname
            </a>
        @endcan
    </div>
</div>

<div class="card data-card">
    <div class="card-header bg-white py-3">
        <form action="{{ route('opname.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai (Stok Updated)</option>
                </select>
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
                    <th class="ps-4" style="width: 5%">No</th>
                    <th style="width: 20%">Nomor Opname</th>
                    <th style="width: 20%">Tanggal</th>
                    <th style="width: 25%">Petugas</th>
                    <th style="width: 15%">Status</th>
                    <th class="text-end pe-4" style="width: 15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($opnames as $index => $opname)
                    <tr>
                        <td class="ps-4">{{ $opnames->firstItem() + $index }}</td>
                        <td><span class="badge bg-light text-dark font-monospace fw-semibold fs-6">{{ $opname->nomor_opname }}</span></td>
                        <td>{{ $opname->tanggal->format('d M Y') }}</td>
                        <td>{{ $opname->petugas->name ?? '-' }}</td>
                        <td>
                            @if($opname->status === 'draft')
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Draft</span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Selesai</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('opname.show', $opname->id) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($opname->status === 'draft')
                                    @can('opname.edit')
                                        <a href="{{ route('opname.edit', $opname->id) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endcan
                                    @can('opname.delete')
                                        <button type="button" class="btn btn-outline-danger btn-delete" 
                                                data-id="{{ $opname->id }}" 
                                                data-nomor="{{ $opname->nomor_opname }}" 
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                @endif
                            </div>
                            @if($opname->status === 'draft')
                                @can('opname.delete')
                                    <form id="delete-form-{{ $opname->id }}" action="{{ route('opname.destroy', $opname->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            Tidak ada data Stock Opname ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($opnames->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $opnames->links() }}
        </div>
    @endif
</div>

@can('opname.delete')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nomor = this.getAttribute('data-nomor');
                
                Swal.fire({
                    title: 'Hapus Stock Opname?',
                    text: `Apakah Anda yakin ingin menghapus Stock Opname "${nomor}"?`,
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
