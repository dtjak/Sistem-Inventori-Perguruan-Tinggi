@extends('layouts.app')
@section('title', 'Mulai Stock Opname')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('opname.index') }}" class="text-decoration-none">Stock Opname</a></li>
    <li class="breadcrumb-item active">Mulai</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card data-card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-play-circle text-primary me-2"></i>Mulai Stock Opname</h5>
            </div>
            <form action="{{ route('opname.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                                   value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Catatan Opname</label>
                            <input type="text" name="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                                   value="{{ old('catatan') }}" placeholder="Contoh: Stock opname bulanan / akhir semester">
                            @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-box me-2 text-secondary"></i>Item Barang & Stok Fisik</h5>
                    
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
                                @foreach($barangs as $index => $barang)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <input type="hidden" name="details[{{ $index }}][barang_id]" value="{{ $barang->id }}">
                                            <input type="hidden" name="details[{{ $index }}][stok_sistem]" value="{{ $barang->stok_saat_ini }}">
                                            <span class="fw-semibold">{{ $barang->nama_barang }}</span>
                                            <span class="text-muted d-block small">{{ $barang->kode_barang }}</span>
                                        </td>
                                        <td class="text-center fw-bold">{{ $barang->stok_saat_ini }} {{ $barang->satuan }}</td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" name="details[{{ $index }}][stok_fisik]" 
                                                       class="form-control text-center @error('details.'.$index.'.stok_fisik') is-invalid @enderror"
                                                       value="{{ old('details.'.$index.'.stok_fisik', $barang->stok_saat_ini) }}" 
                                                       min="0" required>
                                                <span class="input-group-text">{{ $barang->satuan }}</span>
                                                @error('details.'.$index.'.stok_fisik')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="details[{{ $index }}][keterangan]" class="form-control form-control-sm" 
                                                   value="{{ old('details.'.$index.'.keterangan') }}" placeholder="Tulis alasan jika ada selisih...">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-white d-flex gap-2 justify-content-end py-3">
                    <a href="{{ route('opname.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Opname (Draft)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
