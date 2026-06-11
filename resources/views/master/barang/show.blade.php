@extends('layouts.app')
@section('title', 'Detail Barang')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('master.barang.index') }}" class="text-decoration-none">Master Barang</a></li>
    <li class="breadcrumb-item active">{{ $barang->kode_barang }}</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card data-card">
            <div class="card-body text-center py-4">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-3 p-3 mb-3">
                        <i class="bi bi-box-seam text-primary" style="font-size:3rem;"></i>
                    </div>
                </div>
                <h5 class="fw-bold">{{ $barang->nama_barang }}</h5>
                <p class="text-muted mb-3"><code>{{ $barang->kode_barang }}</code></p>

                <div class="mb-3">
                    @if($barang->stok_saat_ini === 0)
                        <span class="badge bg-danger fs-6 px-3 py-2">Stok Habis</span>
                    @elseif($barang->stok_saat_ini <= $barang->stok_minimum)
                        <span class="badge bg-warning text-dark fs-6 px-3 py-2">Stok Menipis</span>
                    @else
                        <span class="badge bg-success fs-6 px-3 py-2">Stok Tersedia</span>
                    @endif
                </div>

                <div class="row g-2 text-center">
                    <div class="col-6">
                        <div class="bg-light rounded p-2">
                            <div class="fw-bold fs-4 text-primary">{{ $barang->stok_saat_ini }}</div>
                            <div class="text-muted small">Stok Saat Ini</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-2">
                            <div class="fw-bold fs-4 text-warning">{{ $barang->stok_minimum }}</div>
                            <div class="text-muted small">Stok Minimum</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white d-flex gap-2">
                @can('barang.edit')
                <a href="{{ route('master.barang.edit', $barang->id) }}" class="btn btn-primary btn-sm flex-fill">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                @endcan
                @can('barang.delete')
                <form action="{{ route('master.barang.destroy', $barang->id) }}" method="POST" class="flex-fill">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100 btn-delete">
                        <i class="bi bi-trash me-1"></i>Hapus
                    </button>
                </form>
                @endcan
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card data-card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-bold">Informasi Detail</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Kategori</dt>
                    <dd class="col-sm-8"><span class="badge bg-light text-dark">{{ $barang->kategori }}</span></dd>
                    <dt class="col-sm-4 text-muted">Satuan</dt>
                    <dd class="col-sm-8">{{ $barang->satuan }}</dd>
                    <dt class="col-sm-4 text-muted">Lokasi Gudang</dt>
                    <dd class="col-sm-8">{{ $barang->lokasi_gudang ?? '-' }}</dd>
                    <dt class="col-sm-4 text-muted">Status</dt>
                    <dd class="col-sm-8">
                        <span class="badge {{ $barang->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($barang->status) }}
                        </span>
                    </dd>
                    <dt class="col-sm-4 text-muted">Keterangan</dt>
                    <dd class="col-sm-8">{{ $barang->keterangan ?? '-' }}</dd>
                    <dt class="col-sm-4 text-muted">Dibuat</dt>
                    <dd class="col-sm-8">{{ $barang->created_at->format('d/m/Y H:i') }}</dd>
                </dl>
            </div>
        </div>

        <div class="card data-card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Riwayat Transaksi</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 table-sm">
                        <thead class="table-light">
                            <tr><th>Nomor SR</th><th>Tanggal</th><th>Unit</th><th>Qty</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse($barang->storeRequisitionDetails->take(10) as $detail)
                            <tr>
                                <td><a href="{{ route('sr.show', $detail->storeRequisition->id) }}">{{ $detail->storeRequisition->nomor_sr }}</a></td>
                                <td>{{ $detail->storeRequisition->tanggal->format('d/m/Y') }}</td>
                                <td>{{ $detail->storeRequisition->unit_peminjam }}</td>
                                <td>{{ $detail->qty }} {{ $barang->satuan }}</td>
                                <td>{!! $detail->storeRequisition->status_badge !!}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">Belum ada transaksi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
