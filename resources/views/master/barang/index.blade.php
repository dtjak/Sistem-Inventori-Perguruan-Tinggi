@extends('layouts.app')
@section('title', 'Master Barang')
@section('breadcrumb')
    <li class="breadcrumb-item active">Master Barang</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-box-seam text-primary me-2"></i>Master Barang</h4>
        <p class="text-muted mb-0">Kelola data barang inventori</p>
    </div>
    <div class="d-flex gap-2">
        @can('barang.export')
        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-download me-1"></i>Export
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('master.barang.export.excel') }}"><i class="bi bi-file-excel me-2 text-success"></i>Excel</a></li>
                <li><a class="dropdown-item" href="{{ route('master.barang.export.pdf') }}"><i class="bi bi-file-pdf me-2 text-danger"></i>PDF</a></li>
            </ul>
        </div>
        @endcan

        @can('barang.import')
        <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-upload me-1"></i>Import
        </button>
        @endcan

        @can('barang.create')
        <a href="{{ route('master.barang.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Tambah Barang
        </a>
        @endcan
    </div>
</div>

<!-- Filter -->
<div class="card data-card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-semibold mb-1">Cari</label>
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Kode atau nama barang..." value="{{ $filters['search'] ?? '' }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Kategori</label>
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kategori)
                    <option value="{{ $kategori }}" {{ ($filters['kategori'] ?? '') === $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="aktif" {{ ($filters['status'] ?? '') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ ($filters['status'] ?? '') === 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-search me-1"></i>Cari
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
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th class="text-center">Stok Min</th>
                        <th class="text-center">Stok Saat Ini</th>
                        <th>Lokasi</th>
                        <th>Status Stok</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $barang)
                    <tr>
                        <td><code class="text-primary">{{ $barang->kode_barang }}</code></td>
                        <td class="fw-semibold">{{ $barang->nama_barang }}</td>
                        <td><span class="badge bg-light text-dark">{{ $barang->kategori }}</span></td>
                        <td>{{ $barang->satuan }}</td>
                        <td class="text-center">{{ $barang->stok_minimum }}</td>
                        <td class="text-center fw-bold
                            {{ $barang->stok_saat_ini === 0 ? 'text-danger' : ($barang->stok_saat_ini <= $barang->stok_minimum ? 'text-warning' : 'text-success') }}">
                            {{ $barang->stok_saat_ini }}
                        </td>
                        <td class="text-muted small">{{ $barang->lokasi_gudang ?? '-' }}</td>
                        <td>
                            @if($barang->stok_saat_ini === 0)
                                <span class="badge bg-danger">Habis</span>
                            @elseif($barang->stok_saat_ini <= $barang->stok_minimum)
                                <span class="badge bg-warning text-dark">Menipis</span>
                            @else
                                <span class="badge bg-success">Tersedia</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $barang->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($barang->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                @can('barang.view')
                                <a href="{{ route('master.barang.show', $barang->id) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @endcan
                                @can('barang.edit')
                                <a href="{{ route('master.barang.edit', $barang->id) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                @can('barang.delete')
                                <form action="{{ route('master.barang.destroy', $barang->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-delete" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>Tidak ada data barang
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                Menampilkan {{ $barangs->firstItem() ?? 0 }}-{{ $barangs->lastItem() ?? 0 }} dari {{ $barangs->total() }} barang
            </div>
            {{ $barangs->links() }}
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Import Data Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('master.barang.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info small">
                        <i class="bi bi-info-circle me-1"></i>
                        Upload file Excel (.xlsx, .xls, .csv) dengan kolom: nama_barang, kategori, satuan, stok_minimum, stok_saat_ini, lokasi_gudang, status
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Excel</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i>Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
