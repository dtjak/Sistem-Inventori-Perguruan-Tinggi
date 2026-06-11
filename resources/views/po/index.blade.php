@extends('layouts.app')
@section('title', isset($isRiwayat) ? 'Riwayat Purchase Order' : 'Purchase Order')
@section('breadcrumb')
    @if(isset($isRiwayat))
        <li class="breadcrumb-item"><a href="{{ route('po.index') }}" class="text-decoration-none">Purchase Order</a></li>
        <li class="breadcrumb-item active">Riwayat</li>
    @else
        <li class="breadcrumb-item active">Purchase Order</li>
    @endif
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ isset($isRiwayat) ? 'Riwayat Purchase Order (Dikirim)' : 'Purchase Order (PO)' }}</h4>
        <p class="text-muted mb-0">{{ isset($isRiwayat) ? 'Daftar PO yang telah dikirimkan ke penerima (Staff Inventori)' : 'Kelola pemesanan pembelian ke supplier rekanan' }}</p>
    </div>
    <div>
        @if(!isset($isRiwayat))
            @can('po.create')
                <a href="{{ route('po.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Buat PO Manual
                </a>
            @endcan
        @endif
    </div>
</div>

<div class="card data-card">
    <div class="card-header bg-white py-3">
        <form action="{{ isset($isRiwayat) ? route('po.riwayat') : route('po.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="{{ isset($isRiwayat) ? 'col-md-8' : 'col-md-4' }}">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" 
                           placeholder="Cari nomor PO..." value="{{ request('search') }}">
                </div>
            </div>
            @if(!isset($isRiwayat))
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="menunggu_head_purchasing" {{ request('status') === 'menunggu_head_purchasing' ? 'selected' : '' }}>Pending Head Purchasing</option>
                    <option value="menunggu_finance" {{ request('status') === 'menunggu_finance' ? 'selected' : '' }}>Pending Finance</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui (Aktif)</option>
                    <option value="dikirim" {{ request('status') === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            @endif
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-funnel me-1"></i> Cari
                </button>
            </div>
            @if(request('search') || (!isset($isRiwayat) && request('status')))
                <div class="col-md-2">
                    <a href="{{ isset($isRiwayat) ? route('po.riwayat') : route('po.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase font-size-11">
                <tr>
                    <th class="ps-4" style="width: 5%">No</th>
                    <th style="width: 15%">Nomor PO</th>
                    <th style="width: 20%">Supplier</th>
                    <th style="width: 13%">Tanggal</th>
                    <th style="width: 13%">Tgl Kirim</th>
                    <th style="width: 15%">Total</th>
                    <th style="width: 12%">Status</th>
                    <th class="text-end pe-4" style="width: 7%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pos as $index => $po)
                    <tr>
                        <td class="ps-4">{{ $pos->firstItem() + $index }}</td>
                        <td><span class="badge bg-light text-dark font-monospace fw-semibold fs-6">{{ $po->nomor_po }}</span></td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $po->supplier->nama_supplier ?? '-' }}</div>
                            <div class="text-muted small">PIC: {{ $po->supplier->pic ?? '-' }}</div>
                        </td>
                        <td>{{ $po->tanggal->format('d M Y') }}</td>
                        <td>{{ $po->tanggal_kirim ? $po->tanggal_kirim->format('d M Y') : '-' }}</td>
                        <td class="fw-bold">Rp {{ number_format($po->total, 0, ',', '.') }}</td>
                        <td>
                             @if($po->status === 'draft')
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Draft</span>
                            @elseif($po->status === 'menunggu_head_purchasing')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1" style="font-size: 10px;">Pending Head Purchasing</span>
                            @elseif($po->status === 'menunggu_finance')
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1" style="font-size: 10px;">Pending Finance</span>
                            @elseif($po->status === 'approved')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Approved</span>
                            @elseif($po->status === 'dikirim')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">Dikirim</span>
                            @elseif($po->status === 'selesai')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Selesai</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Ditolak</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('po.show', $po->id) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($po->status === 'draft')
                                    @can('po.edit')
                                        <a href="{{ route('po.edit', $po->id) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endcan
                                    @can('po.delete')
                                        <button type="button" class="btn btn-outline-danger btn-delete" 
                                                data-id="{{ $po->id }}" 
                                                data-nomor="{{ $po->nomor_po }}" 
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                @endif
                            </div>
                            @if($po->status === 'draft')
                                @can('po.delete')
                                    <form id="delete-form-{{ $po->id }}" action="{{ route('po.destroy', $po->id) }}" method="POST" class="d-none">
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
                            Tidak ada data PO ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pos->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $pos->links() }}
        </div>
    @endif
</div>

@can('po.delete')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nomor = this.getAttribute('data-nomor');
                
                Swal.fire({
                    title: 'Hapus PO?',
                    text: `Apakah Anda yakin ingin menghapus PO "${nomor}"?`,
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
