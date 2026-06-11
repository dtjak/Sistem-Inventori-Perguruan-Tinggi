@extends('layouts.app')
@section('title', 'Buat Retur Barang')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('retur.index') }}" class="text-decoration-none">Retur Barang</a></li>
    <li class="breadcrumb-item active">Buat</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card data-card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle text-primary me-2"></i>Buat Retur Barang</h5>
                @if($rr)
                    <span class="text-muted small">Merujuk ke RR: <a href="{{ route('rr.show', $rr->id) }}" target="_blank" class="fw-semibold">{{ $rr->nomor_rr }}</a></span>
                @else
                    <span class="text-muted small">Merujuk ke PO: <a href="{{ route('po.show', $po->id) }}" target="_blank" class="fw-semibold">{{ $po->nomor_po }}</a></span>
                @endif
            </div>
            <form action="{{ route('retur.store') }}" method="POST">
                @csrf
                @if($rr)
                    <input type="hidden" name="receiving_report_id" value="{{ $rr->id }}">
                    <input type="hidden" name="supplier_id" value="{{ $rr->purchaseOrder->supplier_id ?? '' }}">
                @else
                    <input type="hidden" name="purchase_order_id" value="{{ $po->id }}">
                    <input type="hidden" name="supplier_id" value="{{ $po->supplier_id }}">
                @endif
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Supplier Tujuan</label>
                            <input type="text" class="form-control bg-light" value="{{ $rr ? ($rr->purchaseOrder->supplier->nama_supplier ?? '-') : ($po->supplier->nama_supplier ?? '-') }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Retur <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                                   value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Alasan Retur <span class="text-danger">*</span></label>
                            <input type="text" name="alasan" class="form-control @error('alasan') is-invalid @enderror" 
                                   value="{{ old('alasan') }}" placeholder="Contoh: Barang pecah/rusak pada saat pengiriman" required>
                            @error('alasan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Catatan Tambahan</label>
                            <input type="text" name="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                                   value="{{ old('catatan') }}" placeholder="Tulis instruksi retur lainnya...">
                            @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-box me-2 text-secondary"></i>Item Barang yang Diretur</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th class="text-center" style="width: 20%">{{ $rr ? 'Qty Diterima (RR)' : 'Qty Dipesan (PO)' }}</th>
                                    @if($rr)
                                        <th class="text-center" style="width: 20%">Kondisi RR</th>
                                    @endif
                                    <th class="text-center" style="width: 25%">Qty Diretur <span class="text-danger">*</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($rr)
                                    @foreach($rr->details->where('kondisi', '!=', 'baik') as $index => $detail)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="details[{{ $index }}][barang_id]" value="{{ $detail->barang_id }}">
                                                <span class="fw-semibold">{{ $detail->barang->nama_barang }}</span>
                                                <span class="text-muted d-block small">{{ $detail->barang->kode_barang }}</span>
                                            </td>
                                            <td class="text-center fw-semibold">{{ $detail->qty_diterima }} {{ $detail->barang->satuan }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $detail->kondisi === 'rusak' ? 'danger' : 'warning' }}-subtle text-{{ $detail->kondisi === 'rusak' ? 'danger' : 'warning' }} border px-2 py-1">
                                                    {{ $detail->kondisi }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" name="details[{{ $index }}][qty]" 
                                                           class="form-control text-center @error('details.'.$index.'.qty') is-invalid @enderror"
                                                           value="{{ old('details.'.$index.'.qty', $detail->qty_diterima) }}" 
                                                           min="1" max="{{ $detail->qty_diterima }}" required>
                                                    <span class="input-group-text">{{ $detail->barang->satuan }}</span>
                                                    @error('details.'.$index.'.qty')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach($po->details as $index => $detail)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="details[{{ $index }}][barang_id]" value="{{ $detail->barang_id }}">
                                                <span class="fw-semibold">{{ $detail->barang->nama_barang }}</span>
                                                <span class="text-muted d-block small">{{ $detail->barang->kode_barang }}</span>
                                            </td>
                                            <td class="text-center fw-semibold">{{ $detail->qty }} {{ $detail->barang->satuan }}</td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" name="details[{{ $index }}][qty]" 
                                                           class="form-control text-center @error('details.'.$index.'.qty') is-invalid @enderror"
                                                           value="{{ old('details.'.$index.'.qty', $detail->qty) }}" 
                                                           min="0" max="{{ $detail->qty }}" required>
                                                    <span class="input-group-text">{{ $detail->barang->satuan }}</span>
                                                    @error('details.'.$index.'.qty')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-white d-flex gap-2 justify-content-end py-3">
                    <a href="{{ $rr ? route('rr.show', $rr->id) : route('po.show', $po->id) }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Retur (Draft)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
