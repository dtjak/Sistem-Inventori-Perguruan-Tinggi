@extends('layouts.app')
@section('title', 'Tambah Aset')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('master.aset.index') }}" class="text-decoration-none">Master Aset</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card data-card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle text-primary me-2"></i>Tambah Aset</h5>
            </div>
            <form action="{{ route('master.aset.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Aset <span class="text-muted small">(auto)</span></label>
                            <input type="text" class="form-control bg-light" value="{{ $kode }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Aset <span class="text-danger">*</span></label>
                            <input type="text" name="nama_aset" class="form-control @error('nama_aset') is-invalid @enderror"
                                   value="{{ old('nama_aset') }}" placeholder="Nama aset..." required>
                            @error('nama_aset')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori Aset <span class="text-danger">*</span></label>
                            <input type="text" name="kategori_aset" class="form-control @error('kategori_aset') is-invalid @enderror"
                                   value="{{ old('kategori_aset') }}" placeholder="Gedung, Alat Kantor, Kendaraan, dll..." list="kategoriList" required>
                            <datalist id="kategoriList">
                                <option value="Gedung & Bangunan">
                                  <option value="Peralatan Kantor">
                                  <option value="Kendaraan Dinas">
                                  <option value="Mesin & Alat Berat">
                                  <option value="Perpustakaan & Buku">
                            </datalist>
                            @error('kategori_aset')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                                   value="{{ old('lokasi') }}" placeholder="Ruang A.201, Gedung Utama, dll...">
                            @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kondisi <span class="text-danger">*</span></label>
                            <select name="kondisi" class="form-select @error('kondisi') is-invalid @enderror" required>
                                <option value="baik" {{ old('kondisi') === 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak_ringan" {{ old('kondisi') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="rusak_berat" {{ old('kondisi') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                <option value="tidak_layak" {{ old('kondisi') === 'tidak_layak' ? 'selected' : '' }}>Tidak Layak</option>
                            </select>
                            @error('kondisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Perolehan</label>
                            <input type="date" name="tanggal_perolehan" class="form-control @error('tanggal_perolehan') is-invalid @enderror"
                                   value="{{ old('tanggal_perolehan') }}">
                            @error('tanggal_perolehan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nilai Perolehan (Rp)</label>
                            <input type="number" name="nilai_perolehan" class="form-control @error('nilai_perolehan') is-invalid @enderror"
                                   value="{{ old('nilai_perolehan') }}" min="0" placeholder="Contoh: 15000000">
                            @error('nilai_perolehan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Umur Manfaat (Tahun)</label>
                            <input type="number" name="umur_manfaat" class="form-control @error('umur_manfaat') is-invalid @enderror"
                                   value="{{ old('umur_manfaat') }}" min="1" placeholder="Contoh: 5">
                            @error('umur_manfaat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3"
                                      placeholder="Keterangan atau spesifikasi aset...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex gap-2 justify-content-end py-3">
                    <a href="{{ route('master.aset.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Aset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
