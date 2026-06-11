@extends('layouts.app')
@section('title', 'Edit Receiving Report - ' . $rr->nomor_rr)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('rr.index') }}" class="text-decoration-none">Receiving Report</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card data-card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Receiving Report (RR)</h5>
                <span class="text-muted small">Nomor RR: <span class="fw-semibold text-dark">{{ $rr->nomor_rr }}</span></span>
            </div>
            <form action="{{ route('rr.update', $rr->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Terima Barang <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_terima" class="form-control @error('tanggal_terima') is-invalid @enderror" 
                                   value="{{ old('tanggal_terima', $rr->tanggal_terima->format('Y-m-d')) }}" required>
                            @error('tanggal_terima')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Catatan Penerimaan / Surat Jalan</label>
                            <input type="text" name="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                                   value="{{ old('catatan', $rr->catatan) }}">
                            @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-box me-2 text-secondary"></i>Item Barang</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th class="text-center" style="width: 15%">Qty Dipesan</th>
                                    <th class="text-center" style="width: 25%">Qty Diterima <span class="text-danger">*</span></th>
                                    <th class="text-center" style="width: 20%">Kondisi Barang <span class="text-danger">*</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rr->details as $index => $detail)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="details[{{ $index }}][barang_id]" value="{{ $detail->barang_id }}">
                                            <input type="hidden" name="details[{{ $index }}][qty_dipesan]" value="{{ $detail->qty_dipesan }}">
                                            <span class="fw-semibold">{{ $detail->barang->nama_barang }}</span>
                                            <span class="text-muted d-block small">{{ $detail->barang->kode_barang }}</span>
                                        </td>
                                        <td class="text-center fw-bold">{{ $detail->qty_dipesan }} {{ $detail->barang->satuan }}</td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" name="details[{{ $index }}][qty_diterima]" 
                                                       class="form-control text-center @error('details.'.$index.'.qty_diterima') is-invalid @enderror"
                                                       value="{{ old('details.'.$index.'.qty_diterima', $detail->qty_diterima) }}" 
                                                       min="0" required>
                                                <span class="input-group-text">{{ $detail->barang->satuan }}</span>
                                                @error('details.'.$index.'.qty_diterima')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </td>
                                        <td>
                                            <select name="details[{{ $index }}][kondisi]" class="form-select @error('details.'.$index.'.kondisi') is-invalid @enderror" required>
                                                <option value="baik" {{ old('details.'.$index.'.kondisi', $detail->kondisi) === 'baik' ? 'selected' : '' }}>Baik</option>
                                                <option value="rusak" {{ old('details.'.$index.'.kondisi', $detail->kondisi) === 'rusak' ? 'selected' : '' }}>Rusak</option>
                                                <option value="kurang" {{ old('details.'.$index.'.kondisi', $detail->kondisi) === 'kurang' ? 'selected' : '' }}>Kurang</option>
                                            </select>
                                            @error('details.'.$index.'.kondisi')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-white d-flex gap-2 justify-content-end py-3">
                    <a href="{{ route('rr.show', $rr->id) }}" class="btn btn-secondary">
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
