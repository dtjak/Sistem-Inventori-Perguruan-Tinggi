@extends('layouts.app')
@section('title', 'Laporan Barang')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}" class="text-decoration-none">Laporan</a></li>
    <li class="breadcrumb-item active">Barang</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Laporan Master Barang</h4>
        <p class="text-muted mb-0">Daftar inventori barang lengkap dengan status stok minimum</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success">
            <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
        </a>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
        </a>
    </div>
</div>

<div class="card data-card">
    <div class="card-header bg-white py-3">
        <form action="{{ route('laporan.barang') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="kategori" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori }}" {{ request('kategori') === $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
            @if(request('kategori') || request('status'))
                <div class="col-md-2">
                    <a href="{{ route('laporan.barang') }}" class="btn btn-light w-100">Reset</a>
                </div>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase font-size-11">
                <tr>
                    <th class="ps-4">No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th class="text-center">Stok Gudang</th>
                    <th class="text-center">Stok Minimum</th>
                    <th class="text-center">Status Stok</th>
                    <th>Lokasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                    <tr>
                        <td class="ps-4">{{ $index + 1 }}</td>
                        <td><span class="badge bg-light text-dark font-monospace">{{ $item->kode_barang }}</span></td>
                        <td class="fw-semibold">{{ $item->nama_barang }}</td>
                        <td>{{ $item->kategori }}</td>
                        <td class="text-center fw-bold">{{ $item->stok_saat_ini }} {{ $item->satuan }}</td>
                        <td class="text-center text-muted">{{ $item->stok_minimum }} {{ $item->satuan }}</td>
                        <td class="text-center">
                            @if($item->stok_saat_ini <= 0)
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Habis</span>
                            @elseif($item->stok_saat_ini <= $item->stok_minimum)
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">Menipis</span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Aman</span>
                            @endif
                        </td>
                        <td>{{ $item->lokasi_gudang ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            Tidak ada data barang untuk kriteria ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
