@extends('layouts.app')
@section('title', 'Detail Stock Opname - ' . $opname->nomor_opname)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('opname.index') }}" class="text-decoration-none">Stock Opname</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card data-card mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle text-info me-2"></i>Detail Stock Opname</h5>
                    <span class="text-muted small">Nomor Opname: <span class="fw-semibold text-dark">{{ $opname->nomor_opname }}</span></span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('opname.cetak', $opname->id) }}" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th class="text-muted" style="width: 40%">Tanggal Opname</th>
                                <td>: {{ $opname->tanggal->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Status</th>
                                <td>: 
                                    @if($opname->status === 'draft')
                                        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">Draft</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border px-2 py-1">Selesai (Stok Terkunci)</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th class="text-muted" style="width: 40%">Pelaksana / Petugas</th>
                                <td>: {{ $opname->petugas->name ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($opname->catatan)
                    <div class="alert alert-light border mb-4">
                        <h6 class="fw-bold"><i class="bi bi-chat-left-text me-2"></i>Catatan Opname:</h6>
                        <p class="mb-0 text-secondary" style="white-space: pre-wrap;">{{ $opname->catatan }}</p>
                    </div>
                @endif

                <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-box me-2 text-secondary"></i>Item Hasil Pencocokan Stok</h5>
                
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light text-uppercase font-size-11">
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center">Stok Sistem</th>
                                <th class="text-center">Stok Fisik Gudang</th>
                                <th class="text-center">Selisih (Adjustment)</th>
                                <th>Keterangan / Temuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($opname->details as $index => $detail)
                                @php $selisih = $detail->stok_fisik - $detail->stok_sistem; @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-light text-dark font-monospace">{{ $detail->barang->kode_barang }}</span></td>
                                    <td class="fw-semibold">{{ $detail->barang->nama_barang }}</td>
                                    <td class="text-center">{{ $detail->barang->kategori }}</td>
                                    <td class="text-center">{{ $detail->stok_sistem }} {{ $detail->barang->satuan }}</td>
                                    <td class="text-center fw-bold text-primary">{{ $detail->stok_fisik }} {{ $detail->barang->satuan }}</td>
                                    <td class="text-center fw-semibold text-{{ $selisih == 0 ? 'muted' : ($selisih > 0 ? 'success' : 'danger') }}">
                                        @if($selisih > 0)
                                            +{{ $selisih }} {{ $detail->barang->satuan }}
                                        @elseif($selisih < 0)
                                            {{ $selisih }} {{ $detail->barang->satuan }}
                                        @else
                                            0 (Cocok)
                                        @endif
                                    </td>
                                    <td class="text-secondary small">{{ $detail->keterangan ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card-footer bg-white d-flex gap-2 justify-content-between py-3">
                <a href="{{ route('opname.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                
                <div class="d-flex gap-2">
                    @if($opname->status === 'draft')
                        @can('opname.edit')
                            <a href="{{ route('opname.edit', $opname->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i>Edit Opname
                            </a>
                        @endcan
                        @can('opname.create')
                            <form id="form-selesai-opname" action="{{ route('opname.selesai', $opname->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="button" class="btn btn-success btn-selesai-opname">
                                    <i class="bi bi-check-circle me-1"></i>Selesaikan & Update Stok Sistem
                                </button>
                            </form>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnSelesai = document.querySelector('.btn-selesai-opname');
        if (btnSelesai) {
            btnSelesai.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Selesaikan Stock Opname?',
                    text: 'Apakah Anda yakin ingin menyelesaikan Stock Opname ini? Data stok di sistem akan disesuaikan secara otomatis dan permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Selesaikan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-selesai-opname').submit();
                    }
                });
            });
        }
    });
</script>
@endpush
@endsection
