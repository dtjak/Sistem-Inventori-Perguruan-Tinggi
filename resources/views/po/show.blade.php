@extends('layouts.app')
@section('title', 'Detail Purchase Order - ' . $po->nomor_po)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('po.index') }}" class="text-decoration-none">Purchase Order</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card data-card mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle text-info me-2"></i>Detail Purchase Order</h5>
                    <span class="text-muted small">Nomor PO: <span class="fw-semibold text-dark">{{ $po->nomor_po }}</span></span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('po.cetak', $po->id) }}" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th class="text-muted" style="width: 40%">Tanggal PO</th>
                                <td>: {{ $po->tanggal->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Estimasi Tgl Kirim</th>
                                <td>: {{ $po->tanggal_kirim ? $po->tanggal_kirim->format('d M Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Status</th>
                                <td>: 
                                    @if($po->status === 'draft')
                                        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">Draft</span>
                                    @elseif($po->status === 'menunggu_head_purchasing')
                                        <span class="badge bg-warning-subtle text-warning border px-2 py-1">Pending Head Purchasing</span>
                                    @elseif($po->status === 'menunggu_finance')
                                        <span class="badge bg-info-subtle text-info border px-2 py-1">Pending Finance</span>
                                    @elseif($po->status === 'approved')
                                        <span class="badge bg-success-subtle text-success border px-2 py-1">Approved</span>
                                    @elseif($po->status === 'dikirim')
                                        <span class="badge bg-primary-subtle text-primary border px-2 py-1">Dikirim</span>
                                    @elseif($po->status === 'selesai')
                                        <span class="badge bg-success-subtle text-success border px-2 py-1">Selesai</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border px-2 py-1">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th class="text-muted" style="width: 40%">Supplier Rekanan</th>
                                <td>: <a href="{{ route('master.supplier.show', $po->supplier_id) }}" class="fw-bold">{{ $po->supplier->nama_supplier ?? '-' }}</a></td>
                            </tr>
                            <tr>
                                <th class="text-muted">PIC Supplier</th>
                                <td>: {{ $po->supplier->pic ?? '-' }} (Telp: {{ $po->supplier->telepon ?? '-' }})</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Dibuat Oleh</th>
                                <td>: {{ $po->dibuatOleh->name ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($po->catatan)
                    <div class="alert alert-light border mb-4">
                        <h6 class="fw-bold"><i class="bi bi-chat-left-text me-2"></i>Catatan Pembelian:</h6>
                        <p class="mb-0 text-secondary" style="white-space: pre-wrap;">{{ $po->catatan }}</p>
                    </div>
                @endif

                @if($po->alasan_penolakan)
                    <div class="alert alert-danger-subtle border border-danger-subtle mb-4">
                        <h6 class="fw-bold text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Alasan Penolakan:</h6>
                        <p class="mb-0 text-danger" style="white-space: pre-wrap;">{{ $po->alasan_penolakan }}</p>
                    </div>
                @endif

                @if($po->status === 'dikirim' || $po->nomor_resi)
                    <div class="alert alert-info-subtle border border-info-subtle mb-4">
                        <h6 class="fw-bold text-info"><i class="bi bi-truck me-2"></i>Informasi Pengiriman Supplier:</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th style="width: 25%" class="text-muted">No. Resi / Surat Jalan</th>
                                <td class="fw-bold text-dark">: {{ $po->nomor_resi }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Kurir / Ekspedisi / Driver</th>
                                <td class="text-dark">: {{ $po->kurir_ekspedisi }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Tanggal Pengiriman</th>
                                <td class="text-dark">: {{ $po->tanggal_pengiriman ? $po->tanggal_pengiriman->format('d M Y H:i') : '-' }}</td>
                            </tr>
                            @if($po->catatan_pengiriman)
                            <tr>
                                <th class="text-muted">Catatan Pengiriman</th>
                                <td class="text-dark">: {{ $po->catatan_pengiriman }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                @endif

                <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-box me-2 text-secondary"></i>Item Barang yang Dipesan</h5>
                
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light text-uppercase font-size-11">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center" style="width: 15%">Quantity</th>
                                <th class="text-end" style="width: 20%">Harga Satuan</th>
                                <th class="text-end" style="width: 20%">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($po->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-light text-dark font-monospace">{{ $detail->barang->kode_barang }}</span></td>
                                    <td class="fw-semibold">{{ $detail->barang->nama_barang }}</td>
                                    <td class="text-center">{{ $detail->barang->kategori }}</td>
                                    <td class="text-center fw-semibold">{{ $detail->qty }} {{ $detail->barang->satuan }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($detail->qty * $detail->harga, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-light">
                                <td colspan="6" class="text-end fw-bold">Total Pembelian:</td>
                                <td class="text-end fw-bold text-primary">Rp {{ number_format($po->total, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if($po->receivingReports->count() > 0)
                    <h5 class="fw-bold mt-4 mb-3 border-bottom pb-2"><i class="bi bi-check-all me-2 text-success"></i>Riwayat Penerimaan (Receiving Report)</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nomor RR</th>
                                    <th>Tanggal Penerimaan</th>
                                    <th>Penerima</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($po->receivingReports as $rr)
                                    <tr>
                                        <td><a href="{{ route('rr.show', $rr->id) }}" class="font-monospace fw-bold">{{ $rr->nomor_rr }}</a></td>
                                        <td>{{ $rr->tanggal_terima ? $rr->tanggal_terima->format('d M Y') : '-' }}</td>
                                        <td>{{ $rr->penerima->name ?? '-' }}</td>
                                        <td><span class="badge bg-{{ $rr->status == 'disetujui' ? 'success' : ($rr->status == 'ditolak' ? 'danger' : 'warning') }}">{{ $rr->status }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            
            <div class="card-footer bg-white d-flex gap-2 justify-content-between py-3">
                <a href="{{ route('po.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                
                <div class="d-flex gap-2">
                    @if($po->status === 'draft')
                        @can('po.edit')
                            <a href="{{ route('po.edit', $po->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i>Edit PO
                            </a>
                        @endcan
                        <form action="{{ route('po.submit', $po->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-send me-1"></i>Ajukan PO
                            </button>
                        </form>
                    @endif

                    @if($po->status === 'menunggu_head_purchasing')
                        @can('po.approve_head')
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectHeadModal">
                                <i class="bi bi-x-circle me-1"></i>Tolak (Head Purchasing)
                            </button>
                            <form action="{{ route('po.approve.head', $po->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i>Setujui & Teruskan (Head Purchasing)
                                </button>
                            </form>
                        @endcan
                    @endif

                    @if($po->status === 'menunggu_finance')
                        @can('po.approve_finance')
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectFinanceModal">
                                <i class="bi bi-x-circle me-1"></i>Tolak (Finance)
                            </button>
                            <form action="{{ route('po.approve.finance', $po->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i>Verifikasi Budget & Setujui (Finance)
                                </button>
                            </form>
                        @endcan
                    @endif

                    @if($po->status === 'approved' || $po->status === 'dikirim')
                        @can('rr.create')
                            <a href="{{ route('rr.create', ['po_id' => $po->id]) }}" class="btn btn-primary">
                                <i class="bi bi-check-square me-1"></i>Penerimaan Barang (RR)
                            </a>
                        @endcan
                        @can('retur.create')
                            <a href="{{ route('retur.create', ['po_id' => $po->id]) }}" class="btn btn-outline-danger">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Retur Barang
                            </a>
                        @endcan
                    @endif

                    @if(auth()->user()->hasRole('supplier') && $po->status === 'approved')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kirimBarangModal">
                            <i class="bi bi-truck me-1"></i> Kirim Barang
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@can('po.approve_head')
<!-- Reject Head Modal -->
<div class="modal fade" id="rejectHeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('po.reject.head', $po->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tolak Purchase Order (Head Purchasing)</h5>
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
                    <button type="submit" class="btn btn-danger">Tolak PO</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan

@can('po.approve_finance')
<!-- Reject Finance Modal -->
<div class="modal fade" id="rejectFinanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('po.reject.finance', $po->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tolak Purchase Order (Finance)</h5>
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
                    <button type="submit" class="btn btn-danger">Tolak PO</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan
@if(auth()->user()->hasRole('supplier') && $po->status === 'approved')
<!-- Kirim Barang Modal -->
<div class="modal fade" id="kirimBarangModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('po.kirim', $po->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Kirim Barang (Informasi Pengiriman)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. Resi / Surat Jalan <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_resi" class="form-control" placeholder="Contoh: SJ-001/PO/2026" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kurir / Ekspedisi / Driver <span class="text-danger">*</span></label>
                        <input type="text" name="kurir_ekspedisi" class="form-control" placeholder="Contoh: JNE / Driver Toko (Budi)" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan Pengiriman</label>
                        <textarea name="catatan_pengiriman" class="form-control" rows="3" placeholder="Tulis catatan tambahan jika ada..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Konfirmasi Pengiriman</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
