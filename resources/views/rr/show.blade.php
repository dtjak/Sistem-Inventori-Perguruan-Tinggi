@extends('layouts.app')
@section('title', 'Detail Receiving Report - ' . $rr->nomor_rr)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('rr.index') }}" class="text-decoration-none">Receiving Report</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card data-card mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle text-info me-2"></i>Detail Receiving Report</h5>
                    <span class="text-muted small">Nomor RR: <span class="fw-semibold text-dark">{{ $rr->nomor_rr }}</span></span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('rr.cetak', $rr->id) }}" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th class="text-muted" style="width: 40%">Tanggal Terima</th>
                                <td>: {{ $rr->tanggal_terima->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Status</th>
                                <td>: 
                                    @if($rr->status === 'draft')
                                        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">Draft</span>
                                    @elseif($rr->status === 'menunggu_approval')
                                        <span class="badge bg-warning-subtle text-warning border px-2 py-1">Menunggu Approval</span>
                                    @elseif($rr->status === 'approved')
                                        <span class="badge bg-success-subtle text-success border px-2 py-1">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border px-2 py-1">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Rujukan PO</th>
                                <td>: 
                                    @if($rr->purchaseOrder)
                                        <a href="{{ route('po.show', $rr->purchase_order_id) }}" class="fw-bold font-monospace">{{ $rr->purchaseOrder->nomor_po }}</a>
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
                                <th class="text-muted" style="width: 40%">Supplier</th>
                                <td>: {{ $rr->purchaseOrder->supplier->nama_supplier ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Petugas Penerima</th>
                                <td>: {{ $rr->penerima->name ?? '-' }}</td>
                            </tr>
                            @if($rr->approvedBy)
                                <tr>
                                    <th class="text-muted">Disetujui Oleh</th>
                                    <td>: {{ $rr->approvedBy->name }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                @if($rr->catatan)
                    <div class="alert alert-light border mb-4">
                        <h6 class="fw-bold"><i class="bi bi-chat-left-text me-2"></i>Catatan Penerimaan:</h6>
                        <p class="mb-0 text-secondary" style="white-space: pre-wrap;">{{ $rr->catatan }}</p>
                    </div>
                @endif

                @if($rr->alasan_penolakan)
                    <div class="alert alert-danger-subtle border border-danger-subtle mb-4">
                        <h6 class="fw-bold text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Alasan Penolakan:</h6>
                        <p class="mb-0 text-danger" style="white-space: pre-wrap;">{{ $rr->alasan_penolakan }}</p>
                    </div>
                @endif

                <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-box me-2 text-secondary"></i>Item Barang yang Diterima</h5>
                
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light text-uppercase font-size-11">
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th class="text-center">Qty Dipesan</th>
                                <th class="text-center">Qty Diterima</th>
                                <th class="text-center">Selisih</th>
                                <th class="text-center">Kondisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rr->details as $index => $detail)
                                @php $selisih = $detail->qty_diterima - $detail->qty_dipesan; @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-light text-dark font-monospace">{{ $detail->barang->kode_barang }}</span></td>
                                    <td class="fw-semibold">{{ $detail->barang->nama_barang }}</td>
                                    <td class="text-center">{{ $detail->qty_dipesan }} {{ $detail->barang->satuan }}</td>
                                    <td class="text-center fw-bold text-success">{{ $detail->qty_diterima }} {{ $detail->barang->satuan }}</td>
                                    <td class="text-center fw-semibold text-{{ $selisih >= 0 ? 'dark' : 'danger' }}">
                                        {{ $selisih > 0 ? '+' : '' }}{{ $selisih }} {{ $detail->barang->satuan }}
                                    </td>
                                    <td class="text-center">
                                        @if($detail->kondisi === 'baik')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Baik</span>
                                        @elseif($detail->kondisi === 'rusak')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Rusak</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">Kurang</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card-footer bg-white d-flex gap-2 justify-content-between py-3">
                <a href="{{ route('rr.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                
                <div class="d-flex gap-2">
                    @if($rr->status === 'draft')
                        @can('rr.edit')
                            <a href="{{ route('rr.edit', $rr->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i>Edit RR
                            </a>
                        @endcan
                        <form action="{{ route('rr.submit', $rr->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-send me-1"></i>Ajukan RR
                            </button>
                        </form>
                    @endif

                    @if($rr->status === 'menunggu_approval')
                        @can('rr.approve')
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle me-1"></i>Tolak
                            </button>
                            <form action="{{ route('rr.approve', $rr->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i>Setujui & Tambah Stok
                                </button>
                            </form>
                        @endcan
                    @endif

                    @if($rr->status === 'approved' && $rr->details->where('kondisi', '!=', 'baik')->count() > 0)
                        @can('retur.create')
                            <a href="{{ route('retur.create', ['rr_id' => $rr->id]) }}" class="btn btn-outline-danger">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Buat Retur Barang
                            </a>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@can('rr.approve')
<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('rr.reject', $rr->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="rejectModalLabel">Tolak Receiving Report</h5>
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
                    <button type="submit" class="btn btn-danger">Tolak RR</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan
@endsection
