@extends('layouts.app')
@section('title', 'Laporan Inventori')
@section('breadcrumb')
    <li class="breadcrumb-item active">Laporan</li>
@endsection

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1">Laporan Inventori</h4>
    <p class="text-muted mb-0">Pilih modul laporan untuk menampilkan data atau mengekspornya ke PDF/Excel</p>
</div>

<div class="row g-4">
    <!-- Master Data Reports -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary-subtle text-primary rounded p-3 me-3">
                        <i class="bi bi-box fs-4"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-0">Laporan Barang</h5>
                </div>
                <p class="text-muted small">Cek daftar barang, stok minimum, sisa stok gudang, dan filter per kategori.</p>
                <a href="{{ route('laporan.barang') }}" class="btn btn-outline-primary btn-sm w-100">
                    Buka Laporan <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-info-subtle text-info rounded p-3 me-3">
                        <i class="bi bi-building fs-4"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-0">Laporan Aset</h5>
                </div>
                <p class="text-muted small">Tampilkan semua aset perguruan tinggi dengan status kondisi (Baik, Rusak, dll).</p>
                <a href="{{ route('laporan.aset') }}" class="btn btn-outline-info btn-sm w-100">
                    Buka Laporan <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success-subtle text-success rounded p-3 me-3">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-0">Laporan Supplier</h5>
                </div>
                <p class="text-muted small">Tampilkan daftar supplier rekanan aktif beserta kontak dan rating performa.</p>
                <a href="{{ route('laporan.supplier') }}" class="btn btn-outline-success btn-sm w-100">
                    Buka Laporan <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Requisition Reports -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-warning-subtle text-warning rounded p-3 me-3">
                        <i class="bi bi-file-earmark-text fs-4"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-0">Laporan SR</h5>
                </div>
                <p class="text-muted small">Tinjau rekap Store Requisition dari unit kerja, status pengajuan, dan frekuensi permintaan.</p>
                <a href="{{ route('laporan.sr') }}" class="btn btn-outline-warning btn-sm w-100">
                    Buka Laporan <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-danger-subtle text-danger rounded p-3 me-3">
                        <i class="bi bi-truck fs-4"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-0">Laporan DR</h5>
                </div>
                <p class="text-muted small">Rekap Delivery Requisition (distribusi barang) dari gudang ke masing-masing unit kerja.</p>
                <a href="{{ route('laporan.dr') }}" class="btn btn-outline-danger btn-sm w-100">
                    Buka Laporan <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary-subtle text-primary rounded p-3 me-3">
                        <i class="bi bi-journal-check fs-4"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-0">Laporan PR</h5>
                </div>
                <p class="text-muted small">Rekap pengajuan pembelian barang inventori (Purchase Requisition) beserta total estimasi harga.</p>
                <a href="{{ route('laporan.pr') }}" class="btn btn-outline-primary btn-sm w-100">
                    Buka Laporan <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Purchase & Receiving Reports -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-dark-subtle text-dark rounded p-3 me-3">
                        <i class="bi bi-cart fs-4"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-0">Laporan PO</h5>
                </div>
                <p class="text-muted small">Rekap transaksi Purchase Order ke supplier beserta total pengeluaran keuangan.</p>
                <a href="{{ route('laporan.po') }}" class="btn btn-outline-dark btn-sm w-100">
                    Buka Laporan <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success-subtle text-success rounded p-3 me-3">
                        <i class="bi bi-journal-text fs-4"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-0">Laporan Receiving (RR)</h5>
                </div>
                <p class="text-muted small">Rekap penerimaan barang datang dari supplier beserta catatan selisih dan kondisi.</p>
                <a href="{{ route('laporan.rr') }}" class="btn btn-outline-success btn-sm w-100">
                    Buka Laporan <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-danger-subtle text-danger rounded p-3 me-3">
                        <i class="bi bi-arrow-counterclockwise fs-4"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-0">Laporan Retur</h5>
                </div>
                <p class="text-muted small">Tampilkan semua log retur barang rusak yang dikembalikan ke pihak supplier rekanan.</p>
                <a href="{{ route('laporan.retur') }}" class="btn btn-outline-danger btn-sm w-100">
                    Buka Laporan <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Stock Opname -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-info-subtle text-info rounded p-3 me-3">
                        <i class="bi bi-clipboard-check fs-4"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-0">Laporan Stock Opname</h5>
                </div>
                <p class="text-muted small">Tinjau riwayat audit fisik gudang, detail adjustment, dan selisih stok sistem vs lapangan.</p>
                <a href="{{ route('laporan.opname') }}" class="btn btn-outline-info btn-sm w-100">
                    Buka Laporan <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
