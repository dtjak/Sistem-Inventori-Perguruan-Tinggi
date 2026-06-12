@extends('layouts.app')
@section('title', 'Katalog Barang & Aset')
@section('breadcrumb')
    <li class="breadcrumb-item active">Katalog</li>
@endsection

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h4 class="fw-bold mb-1"><i class="bi bi-journal-bookmark text-primary me-2"></i>Katalog Barang & Aset</h4>
        <p class="text-muted mb-0">Daftar inventori barang habis pakai dan aset sarana prasarana yang tersedia</p>
    </div>
    <div class="col-md-6 mt-3 mt-md-0">
        <form action="{{ route('katalog.index') }}" method="GET" id="katalogFilterForm">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" 
                               placeholder="Cari barang/aset..." value="{{ $search }}">
                        @if($search)
                            <button type="submit" class="btn btn-primary">Cari</button>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="category" class="form-select shadow-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $selectedCategory == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="sort" class="form-select shadow-sm" onchange="this.form.submit()">
                        <option value="kategori" {{ $selectedSort == 'kategori' ? 'selected' : '' }}>Urutkan: Kategori</option>
                        <option value="nama_asc" {{ $selectedSort == 'nama_asc' ? 'selected' : '' }}>Nama (A - Z)</option>
                        <option value="nama_desc" {{ $selectedSort == 'nama_desc' ? 'selected' : '' }}>Nama (Z - A)</option>
                    </select>
                </div>
                @if($search || $selectedCategory || $selectedSort != 'kategori')
                    <div class="col-12 text-end mt-1">
                        <a href="{{ route('katalog.index') }}" class="text-decoration-none small text-muted"><i class="bi bi-x-circle me-1"></i>Hapus Filter</a>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Nav Tabs -->
<ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" id="katalogTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active px-4 py-2 fw-semibold d-flex align-items-center gap-2" id="barang-tab" data-bs-toggle="tab" 
                data-bs-target="#barang-pane" type="button" role="tab" aria-controls="barang-pane" aria-selected="true">
            <i class="bi bi-box-seam fs-5"></i> Barang Habis Pakai
            <span class="badge bg-white text-primary ms-1 shadow-sm">{{ $barangs->count() }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link px-4 py-2 fw-semibold d-flex align-items-center gap-2" id="aset-tab" data-bs-toggle="tab" 
                data-bs-target="#aset-pane" type="button" role="tab" aria-controls="aset-pane" aria-selected="false">
            <i class="bi bi-laptop fs-5"></i> Aset & Peralatan
            <span class="badge bg-white text-secondary ms-1 shadow-sm">{{ $asets->count() }}</span>
        </button>
    </li>
</ul>

<div class="tab-content fade-in" id="katalogTabContent">
    <!-- Barang Pane -->
    <div class="tab-pane fade show active" id="barang-pane" role="tabpanel" aria-labelledby="barang-tab" tabindex="0">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            @forelse($barangs as $barang)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm stat-card">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace">{{ $barang->kode_barang }}</span>
                                <span class="badge bg-secondary-subtle text-secondary">{{ $barang->kategori }}</span>
                            </div>
                            <h5 class="card-title fw-bold text-dark mb-2 text-truncate-2" style="height: 48px; line-height: 1.3;">{{ $barang->nama_barang }}</h5>
                            
                            <div class="mt-auto pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Stok Tersedia</span>
                                    <span class="fw-bold fs-5 text-dark">{{ $barang->stok_saat_ini }} <small class="text-muted fw-normal fs-6">{{ $barang->satuan }}</small></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Status</span>
                                    @if($barang->stok_saat_ini > $barang->stok_minimum)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle py-1 px-2 small">Tersedia</span>
                                    @elseif($barang->stok_saat_ini > 0)
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle py-1 px-2 small">Stok Menipis</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle py-1 px-2 small">Habis</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-5 text-center text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    Tidak ada barang ditemukan.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Aset Pane -->
    <div class="tab-pane fade" id="aset-pane" role="tabpanel" aria-labelledby="aset-tab" tabindex="0">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            @forelse($asets as $aset)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm stat-card">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-info-subtle text-info border border-info-subtle font-monospace">{{ $aset->kode_aset }}</span>
                                <span class="badge bg-secondary-subtle text-secondary">{{ $aset->kategori_aset }}</span>
                            </div>
                            <h5 class="card-title fw-bold text-dark mb-2 text-truncate-2" style="height: 48px; line-height: 1.3;">{{ $aset->nama_aset }}</h5>
                            
                            <div class="mt-auto pt-3 border-top">
                                <div class="mb-2">
                                    <small class="text-muted d-block">Lokasi Aset</small>
                                    <span class="fw-semibold text-dark small"><i class="bi bi-geo-alt me-1 text-danger"></i>{{ $aset->lokasi ?? '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Kondisi</span>
                                    {!! $aset->kondisi_badge !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-5 text-center text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    Tidak ada aset ditemukan.
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;  
    overflow: hidden;
}
#katalogTab .nav-link {
    border-radius: 30px;
    background-color: #f8f9fa !important;
    color: #495057 !important;
    border: 1px solid #dee2e6 !important;
    transition: all 0.2s;
}
#katalogTab .nav-link:hover {
    background-color: #e9ecef !important;
    color: #212529 !important;
}
#katalogTab .nav-link.active {
    background: linear-gradient(135deg, var(--accent) 0%, #1a4fc4 100%) !important;
    border-color: transparent !important;
    color: white !important;
}
#katalogTab .nav-link.active .badge {
    background-color: white !important;
    color: var(--accent) !important;
}
#katalogTab .nav-link:not(.active) .badge {
    background-color: #6c757d !important;
    color: white !important;
}
</style>
@endsection
