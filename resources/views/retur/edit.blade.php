@extends('layouts.app')
@section('title', 'Edit Retur - ' . $retur->nomor_retur)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('retur.index') }}" class="text-decoration-none">Retur Barang</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card data-card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Retur Barang</h5>
                <span class="text-muted small">Nomor Retur: <span class="fw-semibold text-dark">{{ $retur->nomor_retur }}</span></span>
            </div>
            <form action="{{ route('retur.update', $retur->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="supplier_id" value="{{ $retur->supplier_id }}">
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Supplier Tujuan</label>
                            <input type="text" class="form-control bg-light" value="{{ $retur->supplier->nama_supplier ?? '-' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Retur <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                                   value="{{ old('tanggal', $retur->tanggal->format('Y-m-d')) }}" required>
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Alasan Retur <span class="text-danger">*</span></label>
                            <input type="text" name="alasan" class="form-control @error('alasan') is-invalid @enderror" 
                                   value="{{ old('alasan', $retur->alasan) }}" required>
                            @error('alasan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Catatan Tambahan</label>
                            <input type="text" name="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                                   value="{{ old('catatan', $retur->catatan) }}">
                            @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-box me-2 text-secondary"></i>Item Barang</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th class="text-center" style="width: 25%">Qty Diretur <span class="text-danger">*</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($retur->details as $index => $detail)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="details[{{ $index }}][barang_id]" value="{{ $detail->barang_id }}">
                                            <span class="fw-semibold">{{ $detail->barang->nama_barang }}</span>
                                            <span class="text-muted d-block small">{{ $detail->barang->kode_barang }}</span>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" name="details[{{ $index }}][qty]" 
                                                       class="form-control text-center @error('details.'.$index.'.qty') is-invalid @enderror"
                                                       value="{{ old('details.'.$index.'.qty', $detail->qty) }}" 
                                                       min="1" required>
                                                <span class="input-group-text">{{ $detail->barang->satuan }}</span>
                                                @error('details.'.$index.'.qty')
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
                    <a href="{{ route('retur.show', $retur->id) }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
