@extends('layouts.app')
@section('title', 'Buat Delivery Requisition')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dr.index') }}" class="text-decoration-none">Delivery Requisition</a></li>
    <li class="breadcrumb-item active">Buat</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card data-card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle text-primary me-2"></i>Buat Delivery Requisition (DR)</h5>
                <span class="text-muted small">Merujuk ke SR: <a href="{{ route('sr.show', $sr->id) }}" target="_blank" class="fw-semibold">{{ $sr->nomor_sr }}</a></span>
            </div>
            <form action="{{ route('dr.store') }}" method="POST">
                @csrf
                <input type="hidden" name="store_requisition_id" value="{{ $sr->id }}">
                
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Pengiriman <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                                   value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pemohon SR / Unit</label>
                            <input type="text" class="form-control bg-light" 
                                   value="{{ $sr->pemohon->name ?? '-' }} ({{ $sr->unit }})" readonly>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Catatan Pengiriman</label>
                            <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                                      rows="2" placeholder="Catatan tambahan untuk kurir/penerima...">{{ old('catatan') }}</textarea>
                            @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-box me-2 text-secondary"></i>Item Barang yang Didistribusikan</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th class="text-center" style="width: 15%">Qty SR</th>
                                    <th class="text-center" style="width: 15%">Stok Saat Ini</th>
                                    <th class="text-center" style="width: 20%">Qty Distribusi <span class="text-danger">*</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sr->details->whereNotNull('barang_id') as $index => $detail)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="details[{{ $index }}][barang_id]" value="{{ $detail->barang_id }}">
                                            <span class="fw-semibold">{{ $detail->barang->nama_barang }}</span>
                                            <span class="text-muted d-block small">{{ $detail->barang->kode_barang }} - Kategori: {{ $detail->barang->kategori }}</span>
                                        </td>
                                        <td class="text-center fw-bold">{{ $detail->qty }} {{ $detail->barang->satuan }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $detail->barang->stok_saat_ini > 0 ? 'success' : 'danger' }}-subtle text-{{ $detail->barang->stok_saat_ini > 0 ? 'success' : 'danger' }} border px-2 py-1">
                                                {{ $detail->barang->stok_saat_ini }} {{ $detail->barang->satuan }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" name="details[{{ $index }}][qty_distribusi]" 
                                                       class="form-control text-center @error('details.'.$index.'.qty_distribusi') is-invalid @enderror"
                                                       value="{{ old('details.'.$index.'.qty_distribusi', min($detail->qty, $detail->barang->stok_saat_ini)) }}" 
                                                       min="1" max="{{ $detail->barang->stok_saat_ini }}" required>
                                                <span class="input-group-text bg-light">{{ $detail->barang->satuan }}</span>
                                                @error('details.'.$index.'.qty_distribusi')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-white d-flex gap-2 justify-content-end py-3">
                    <a href="{{ route('sr.show', $sr->id) }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan DR (Draft)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
