@extends('layouts.app')
@section('title', 'Detail Purchase Requisition - ' . $pr->nomor_pr)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pr.index') }}" class="text-decoration-none">Purchase Requisition</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card data-card mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle text-info me-2"></i>Detail Purchase Requisition</h5>
                    <span class="text-muted small">Nomor PR: <span class="fw-semibold text-dark">{{ $pr->nomor_pr }}</span></span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('pr.cetak', $pr->id) }}" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th class="text-muted" style="width: 40%">Tanggal PR</th>
                                <td>: {{ $pr->tanggal->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Status</th>
                                <td>: 
                                    @if($pr->status === 'draft')
                                        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">Draft</span>
                                    @elseif($pr->status === 'menunggu_approval')
                                        <span class="badge bg-warning-subtle text-warning border px-2 py-1">Menunggu Approval</span>
                                    @elseif($pr->status === 'approved')
                                        <span class="badge bg-success-subtle text-success border px-2 py-1">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border px-2 py-1">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Total Estimasi Anggaran</th>
                                <td>: <span class="fw-bold text-primary">Rp {{ number_format($pr->total_estimasi, 0, ',', '.') }}</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th class="text-muted" style="width: 40%">Dibuat Oleh</th>
                                <td>: {{ $pr->dibuatOleh->name ?? '-' }}</td>
                            </tr>
                            @if($pr->approvedBy)
                                <tr>
                                    <th class="text-muted">Disetujui Oleh</th>
                                    <td>: {{ $pr->approvedBy->name }}</td>
                                </tr>
                            @endif
                            @if($pr->purchaseOrders->count() > 0)
                                <tr>
                                    <th class="text-muted">Rujukan PO</th>
                                    <td>: 
                                        @foreach($pr->purchaseOrders as $po)
                                            <a href="{{ route('po.show', $po->id) }}" class="badge bg-light text-dark border font-monospace me-1">{{ $po->nomor_po }}</a>
                                        @endforeach
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                @if($pr->alasan)
                    <div class="alert alert-light border mb-4">
                        <h6 class="fw-bold"><i class="bi bi-chat-left-text me-2"></i>Alasan Pengajuan:</h6>
                        <p class="mb-0 text-secondary" style="white-space: pre-wrap;">{{ $pr->alasan }}</p>
                    </div>
                @endif

                @if($pr->alasan_penolakan)
                    <div class="alert alert-danger-subtle border border-danger-subtle mb-4">
                        <h6 class="fw-bold text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Alasan Penolakan:</h6>
                        <p class="mb-0 text-danger" style="white-space: pre-wrap;">{{ $pr->alasan_penolakan }}</p>
                    </div>
                @endif

                <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-box me-2 text-secondary"></i>Item Barang yang Diajukan</h5>
                
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light text-uppercase font-size-11">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center" style="width: 15%">Quantity</th>
                                <th class="text-end" style="width: 20%">Estimasi Harga Satuan</th>
                                <th class="text-end" style="width: 20%">Estimasi Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pr->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-light text-dark font-monospace">{{ $detail->barang->kode_barang }}</span></td>
                                    <td class="fw-semibold">{{ $detail->barang->nama_barang }}</td>
                                    <td class="text-center">{{ $detail->barang->kategori }}</td>
                                    <td class="text-center fw-semibold">{{ $detail->qty }} {{ $detail->barang->satuan }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->estimasi_harga, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($detail->qty * $detail->estimasi_harga, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-light">
                                <td colspan="6" class="text-end fw-bold">Total Estimasi:</td>
                                <td class="text-end fw-bold text-primary">Rp {{ number_format($pr->total_estimasi, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card-footer bg-white d-flex gap-2 justify-content-between py-3">
                <a href="{{ route('pr.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                
                <div class="d-flex gap-2">
                    @if(in_array($pr->status, ['draft', 'revisi']))
                        @can('pr.edit')
                            <a href="{{ route('pr.edit', $pr->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i>Edit PR
                            </a>
                        @endcan
                        <form action="{{ route('pr.submit', $pr->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-send me-1"></i>Ajukan PR
                            </button>
                        </form>
                    @endif

                    @if($pr->status === 'menunggu_approval')
                        @can('pr.approve')
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle me-1"></i>Tolak
                            </button>
                            <form action="{{ route('pr.approve', $pr->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i>Setujui
                                </button>
                            </form>
                        @endcan
                    @endif

                    @if($pr->status === 'approved' && $pr->purchaseOrders->count() == 0)
                        @can('po.create')
                            <a href="{{ route('po.create', ['pr_id' => $pr->id]) }}" class="btn btn-primary">
                                <i class="bi bi-cart-plus me-1"></i>Buat PO (Purchase Order)
                            </a>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@can('pr.approve')
<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('pr.reject', $pr->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="rejectModalLabel">Tolak Purchase Requisition</h5>
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
                    <button type="submit" class="btn btn-danger">Tolak PR</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan
@endsection
