@extends('layouts.app')
@section('title', 'Detail Retur Barang - ' . $retur->nomor_retur)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('retur.index') }}" class="text-decoration-none">Retur Barang</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card data-card mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle text-info me-2"></i>Detail Retur Barang</h5>
                    <span class="text-muted small">Nomor Retur: <span class="fw-semibold text-dark">{{ $retur->nomor_retur }}</span></span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('retur.cetak', $retur->id) }}" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th class="text-muted" style="width: 40%">Tanggal Retur</th>
                                <td>: {{ $retur->tanggal->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Status</th>
                                <td>: 
                                    @if($retur->status === 'draft')
                                        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">Draft</span>
                                    @elseif($retur->status === 'menunggu_approval')
                                        <span class="badge bg-warning-subtle text-warning border px-2 py-1">Menunggu Approval</span>
                                    @elseif($retur->status === 'approved')
                                        <span class="badge bg-info-subtle text-info border px-2 py-1">Disetujui</span>
                                    @elseif($retur->status === 'dikirim')
                                        <span class="badge bg-primary-subtle text-primary border px-2 py-1">Dikirim</span>
                                    @elseif($retur->status === 'selesai')
                                        <span class="badge bg-success-subtle text-success border px-2 py-1">Selesai</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border px-2 py-1">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Rujukan RR</th>
                                <td>: 
                                    @if($retur->receivingReport)
                                        <a href="{{ route('rr.show', $retur->receiving_report_id) }}" class="fw-bold font-monospace">{{ $retur->receivingReport->nomor_rr }}</a>
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
                                <th class="text-muted" style="width: 40%">Supplier Tujuan</th>
                                <td>: <a href="{{ route('master.supplier.show', $retur->supplier_id) }}" class="fw-bold">{{ $retur->supplier->nama_supplier ?? '-' }}</a></td>
                            </tr>
                            <tr>
                                <th class="text-muted">Dibuat Oleh</th>
                                <td>: {{ $retur->dibuatOleh->name ?? '-' }}</td>
                            </tr>
                            @if($retur->approvedBy)
                                <tr>
                                    <th class="text-muted">Disetujui Oleh</th>
                                    <td>: {{ $retur->approvedBy->name }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                @if($retur->alasan)
                    <div class="alert alert-light border mb-4">
                        <h6 class="fw-bold"><i class="bi bi-chat-left-text me-2"></i>Alasan Retur:</h6>
                        <p class="mb-0 text-secondary" style="white-space: pre-wrap;">{{ $retur->alasan }}</p>
                    </div>
                @endif

                @if($retur->catatan)
                    <div class="alert alert-light border mb-4">
                        <h6 class="fw-bold"><i class="bi bi-chat-left-text me-2"></i>Catatan Tambahan:</h6>
                        <p class="mb-0 text-secondary" style="white-space: pre-wrap;">{{ $retur->catatan }}</p>
                    </div>
                @endif

                @if($retur->alasan_penolakan)
                    <div class="alert alert-danger-subtle border border-danger-subtle mb-4">
                        <h6 class="fw-bold text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Alasan Penolakan:</h6>
                        <p class="mb-0 text-danger" style="white-space: pre-wrap;">{{ $retur->alasan_penolakan }}</p>
                    </div>
                @endif

                @if($retur->status === 'dikirim' || $retur->nomor_resi)
                    <div class="alert alert-info-subtle border border-info-subtle mb-4">
                        <h6 class="fw-bold text-info"><i class="bi bi-truck me-2"></i>Informasi Pengiriman Barang Pengganti (Supplier):</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th style="width: 25%" class="text-muted">No. Resi / Surat Jalan</th>
                                <td class="fw-bold text-dark">: {{ $retur->nomor_resi }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Kurir / Ekspedisi / Driver</th>
                                <td class="text-dark">: {{ $retur->kurir_ekspedisi }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Tanggal Pengiriman</th>
                                <td class="text-dark">: {{ $retur->tanggal_pengiriman ? $retur->tanggal_pengiriman->format('d M Y H:i') : '-' }}</td>
                            </tr>
                            @if($retur->catatan_pengiriman)
                            <tr>
                                <th class="text-muted">Catatan Pengiriman</th>
                                <td class="text-dark">: {{ $retur->catatan_pengiriman }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                @endif

                <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-box me-2 text-secondary"></i>Item Barang yang Diretur</h5>
                
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light text-uppercase font-size-11">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center" style="width: 20%">Qty Diretur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($retur->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-light text-dark font-monospace">{{ $detail->barang->kode_barang }}</span></td>
                                    <td class="fw-semibold">{{ $detail->barang->nama_barang }}</td>
                                    <td class="text-center">{{ $detail->barang->kategori }}</td>
                                    <td class="text-center fw-bold text-danger">{{ $detail->qty }} {{ $detail->barang->satuan }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card-footer bg-white d-flex gap-2 justify-content-between py-3">
                <a href="{{ route('retur.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                
                <div class="d-flex gap-2">
                    @if($retur->status === 'draft')
                        @can('retur.edit')
                            <a href="{{ route('retur.edit', $retur->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i>Edit Retur
                            </a>
                        @endcan
                        <form action="{{ route('retur.submit', $retur->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-send me-1"></i>Ajukan Retur
                            </button>
                        </form>
                    @endif

                    @if($retur->status === 'menunggu_approval')
                        @can('retur.approve')
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle me-1"></i>Tolak
                            </button>
                            <form action="{{ route('retur.approve', $retur->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i>Setujui Retur (Keluarkan Stok)
                                </button>
                            </form>
                        @endcan
                    @endif

                    @if($retur->status === 'approved')
                        @if(auth()->user()->hasRole('supplier'))
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kirimReturModal">
                                <i class="bi bi-truck me-1"></i> Kirim Barang Pengganti
                            </button>
                        @endif
                    @endif

                    @if($retur->status === 'dikirim')
                        @can('retur.create')
                            <form action="{{ route('retur.selesai', $retur->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i> Barang Diterima & Selesai
                                </button>
                            </form>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@can('retur.approve')
<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('retur.reject', $retur->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="rejectModalLabel">Tolak Retur Barang</h5>
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
                    <button type="submit" class="btn btn-danger">Tolak Retur</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan
@if(auth()->user()->hasRole('supplier') && $retur->status === 'approved')
<!-- Kirim Retur Modal -->
<div class="modal fade" id="kirimReturModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('retur.kirim', $retur->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Kirim Barang Pengganti (Informasi Pengiriman)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. Resi / Surat Jalan <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_resi" class="form-control" placeholder="Contoh: SJ-001/RTR/2026" required>
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
