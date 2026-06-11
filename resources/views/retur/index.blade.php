@extends('layouts.app')
@section('title', 'Retur Barang')
@section('breadcrumb')
    <li class="breadcrumb-item active">Retur Barang</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Retur Barang</h4>
        <p class="text-muted mb-0">Kelola pengembalian barang rusak atau tidak sesuai ke supplier</p>
    </div>
</div>

<div class="card data-card">
    <div class="card-header bg-white py-3">
        <form action="{{ route('retur.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" 
                           placeholder="Cari nomor retur..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="menunggu_approval" {{ request('status') === 'menunggu_approval' ? 'selected' : '' }}>Menunggu Approval</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="dikirim" {{ request('status') === 'dikirim' ? 'selected' : '' }}>Dikirim (Barang Pengganti)</option>
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
                    <a href="{{ route('retur.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase font-size-11">
                <tr>
                    <th class="ps-4" style="width: 5%">No</th>
                    <th style="width: 15%">Nomor Retur</th>
                    <th style="width: 15%">Nomor RR</th>
                    <th style="width: 20%">Supplier</th>
                    <th style="width: 13%">Tanggal</th>
                    <th style="width: 17%">Alasan</th>
                    <th style="width: 10%">Status</th>
                    <th class="text-end pe-4" style="width: 5%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($returs as $index => $retur)
                    <tr>
                        <td class="ps-4">{{ $returs->firstItem() + $index }}</td>
                        <td><span class="badge bg-light text-dark font-monospace fw-semibold fs-6">{{ $retur->nomor_retur }}</span></td>
                        <td>
                            @if($retur->receivingReport)
                                <a href="{{ route('rr.show', $retur->receiving_report_id) }}" class="text-decoration-none font-monospace">{{ $retur->receivingReport->nomor_rr }}</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $retur->supplier->nama_supplier ?? '-' }}</td>
                        <td>{{ $retur->tanggal->format('d M Y') }}</td>
                        <td><div class="text-truncate" style="max-width: 150px;">{{ $retur->alasan }}</div></td>
                        <td>
                            @if($retur->status === 'draft')
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Draft</span>
                            @elseif($retur->status === 'menunggu_approval')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">Menunggu Approval</span>
                            @elseif($retur->status === 'approved')
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">Disetujui</span>
                            @elseif($retur->status === 'dikirim')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">Dikirim</span>
                            @elseif($retur->status === 'selesai')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Selesai</span>
                            @elseif($retur->status === 'ditolak')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Ditolak</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">{{ $retur->status }}</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('retur.show', $retur->id) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($retur->status === 'draft')
                                    @can('retur.edit')
                                        <a href="{{ route('retur.edit', $retur->id) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endcan
                                    @can('retur.delete')
                                        <button type="button" class="btn btn-outline-danger btn-delete" 
                                                data-id="{{ $retur->id }}" 
                                                data-nomor="{{ $retur->nomor_retur }}" 
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                @endif
                            </div>
                            @if($retur->status === 'draft')
                                @can('retur.delete')
                                    <form id="delete-form-{{ $retur->id }}" action="{{ route('retur.destroy', $retur->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            Tidak ada data Retur ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($returs->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $returs->links() }}
        </div>
    @endif
</div>

@can('retur.delete')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nomor = this.getAttribute('data-nomor');
                
                Swal.fire({
                    title: 'Hapus Retur?',
                    text: `Apakah Anda yakin ingin menghapus Retur "${nomor}"?`,
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
