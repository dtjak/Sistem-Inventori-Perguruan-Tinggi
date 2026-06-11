@extends('layouts.app')
@section('title', 'Detail Delivery Requisition - ' . $dr->nomor_dr)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dr.index') }}" class="text-decoration-none">Delivery Requisition</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card data-card mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle text-info me-2"></i>Detail Delivery Requisition</h5>
                    <span class="text-muted small">Nomor DR: <span class="fw-semibold text-dark">{{ $dr->nomor_dr }}</span></span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('dr.cetak', $dr->id) }}" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th class="text-muted" style="width: 40%">Tanggal DR</th>
                                <td>: {{ $dr->tanggal->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Status</th>
                                <td>: 
                                    @if($dr->status === 'draft')
                                        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">Draft</span>
                                    @elseif($dr->status === 'menunggu_approval')
                                        <span class="badge bg-warning-subtle text-warning border px-2 py-1">Menunggu Approval</span>
                                    @elseif($dr->status === 'approved')
                                        <span class="badge bg-info-subtle text-info border px-2 py-1">Approved</span>
                                    @elseif($dr->status === 'selesai')
                                        <span class="badge bg-success-subtle text-success border px-2 py-1">Selesai</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border px-2 py-1">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Merujuk ke SR</th>
                                <td>: 
                                    @if($dr->storeRequisition)
                                        <a href="{{ route('sr.show', $dr->store_requisition_id) }}" class="fw-bold font-monospace">{{ $dr->storeRequisition->nomor_sr }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th class="text-muted" style="width: 40%">Unit Pemohon SR</th>
                                <td>: {{ $dr->storeRequisition->unit ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Dibuat Oleh</th>
                                <td>: {{ $dr->dibuatOleh->name ?? '-' }}</td>
                            </tr>
                            @if($dr->approvedBy)
                                <tr>
                                    <th class="text-muted">Disetujui Oleh</th>
                                    <td>: {{ $dr->approvedBy->name }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                @if($dr->catatan)
                    <div class="alert alert-light border mb-4">
                        <h6 class="fw-bold"><i class="bi bi-chat-left-text me-2"></i>Catatan Pengiriman:</h6>
                        <p class="mb-0 text-secondary" style="white-space: pre-wrap;">{{ $dr->catatan }}</p>
                    </div>
                @endif

                @if($dr->alasan_penolakan)
                    <div class="alert alert-danger-subtle border border-danger-subtle mb-4">
                        <h6 class="fw-bold text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Alasan Penolakan:</h6>
                        <p class="mb-0 text-danger" style="white-space: pre-wrap;">{{ $dr->alasan_penolakan }}</p>
                    </div>
                @endif

                <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-box me-2 text-secondary"></i>Item Barang dalam Pengiriman</h5>
                
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light text-uppercase font-size-11">
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center" style="width: 20%">Qty Pengiriman</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dr->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-light text-dark font-monospace">{{ $detail->barang->kode_barang }}</span></td>
                                    <td class="fw-semibold">{{ $detail->barang->nama_barang }}</td>
                                    <td class="text-center">{{ $detail->barang->kategori }}</td>
                                    <td class="text-center fw-bold">{{ $detail->qty_distribusi }} {{ $detail->barang->satuan }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card-footer bg-white d-flex gap-2 justify-content-between py-3">
                <a href="{{ route('dr.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                
                <div class="d-flex gap-2">
                    @if(in_array($dr->status, ['draft', 'revisi']))
                        @can('dr.edit')
                            <a href="{{ route('dr.edit', $dr->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i>Edit DR
                            </a>
                        @endcan
                        <form action="{{ route('dr.submit', $dr->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-send me-1"></i>Ajukan DR
                            </button>
                        </form>
                    @endif

                    @if($dr->status === 'menunggu_approval')
                        @can('dr.approve')
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle me-1"></i>Tolak
                            </button>
                            <form action="{{ route('dr.approve', $dr->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i>Setujui
                                </button>
                            </form>
                        @endcan
                    @endif

                    @if($dr->status === 'approved')
                        @can('dr.create')
                            <form action="{{ route('dr.selesai', $dr->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-all me-1"></i>Tandai Selesai / Diterima
                                </button>
                            </form>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@can('dr.approve')
<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('dr.reject', $dr->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="rejectModalLabel">Tolak Delivery Requisition</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-content-body p-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_penolakan" class="form-control" rows="4" placeholder="Tulis alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak DR</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan
@endsection
