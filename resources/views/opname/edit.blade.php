@extends('layouts.app')
@section('title', 'Edit Stock Opname - ' . $opname->nomor_opname)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('opname.index') }}" class="text-decoration-none">Stock Opname</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card data-card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Stock Opname</h5>
                <span class="text-muted small">Nomor Opname: <span class="fw-semibold text-dark">{{ $opname->nomor_opname }}</span></span>
            </div>
            <form action="{{ route('opname.update', $opname->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                                   value="{{ old('tanggal', $opname->tanggal->format('Y-m-d')) }}" required>
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Catatan Opname</label>
                            <input type="text" name="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                                   value="{{ old('catatan', $opname->catatan) }}">
                            @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-box me-2 text-secondary"></i>Item Barang</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th>Nama Barang</th>
                                    <th class="text-center" style="width: 15%">Stok Sistem</th>
                                    <th class="text-center" style="width: 25%">Stok Fisik Gudang <span class="text-danger">*</span></th>
                                    <th>Keterangan Selisih</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($opname->details as $index => $detail)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <input type="hidden" name="details[{{ $index }}][barang_id]" value="{{ $detail->barang_id }}">
                                            <input type="hidden" name="details[{{ $index }}][stok_sistem]" value="{{ $detail->stok_sistem }}">
                                            <span class="fw-semibold">{{ $detail->barang->nama_barang }}</span>
                                            <span class="text-muted d-block small">{{ $detail->barang->kode_barang }}</span>
                                        </td>
                                        <td class="text-center fw-bold">{{ $detail->stok_sistem }} {{ $detail->barang->satuan }}</td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" name="details[{{ $index }}][stok_fisik]" 
                                                       class="form-control text-center @error('details.'.$index.'.stok_fisik') is-invalid @enderror"
                                                       value="{{ old('details.'.$index.'.stok_fisik', $detail->stok_fisik) }}" 
                                                       min="0" required>
                                                <span class="input-group-text">{{ $detail->barang->satuan }}</span>
                                                @error('details.'.$index.'.stok_fisik')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="details[{{ $index }}][keterangan]" class="form-control form-control-sm" 
                                                   value="{{ old('details.'.$index.'.keterangan', $detail->keterangan) }}" placeholder="Keterangan selisih...">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-white d-flex gap-2 justify-content-end py-3">
                    <a href="{{ route('opname.show', $opname->id) }}" class="btn btn-secondary">
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
