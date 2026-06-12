@extends('layouts.app')
@section('title', 'Tambah Barang')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('master.barang.index') }}" class="text-decoration-none">Master Barang</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card data-card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle text-primary me-2"></i>Tambah Barang</h5>
            </div>
            <form action="{{ route('master.barang.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Barang <span class="text-muted small">(auto)</span></label>
                            <input type="text" class="form-control bg-light" value="{{ $kode }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror"
                                   value="{{ old('nama_barang') }}" placeholder="Nama barang..." required>
                            @error('nama_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" id="select-kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                <option value="" disabled {{ !old('kategori') ? 'selected' : '' }}>-- Pilih Kategori --</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat }}" {{ old('kategori') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                @endforeach
                                <option value="new_option" class="text-primary fw-semibold">+ Tambah Kategori Baru...</option>
                            </select>
                            @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                            <select name="satuan" id="select-satuan" class="form-select @error('satuan') is-invalid @enderror" required>
                                <option value="" disabled {{ !old('satuan') ? 'selected' : '' }}>-- Pilih Satuan --</option>
                                @foreach($satuans as $sat)
                                    <option value="{{ $sat }}" {{ old('satuan') === $sat ? 'selected' : '' }}>{{ $sat }}</option>
                                @endforeach
                                <option value="new_option" class="text-primary fw-semibold">+ Tambah Satuan Baru...</option>
                            </select>
                            @error('satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Stok Minimum <span class="text-danger">*</span></label>
                            <input type="number" name="stok_minimum" class="form-control @error('stok_minimum') is-invalid @enderror"
                                   value="{{ old('stok_minimum', 0) }}" min="0" required>
                            @error('stok_minimum')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Stok Awal</label>
                            <input type="number" name="stok_saat_ini" class="form-control"
                                   value="{{ old('stok_saat_ini', 0) }}" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="aktif" {{ old('status') !== 'nonaktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Lokasi Gudang</label>
                            <input type="text" name="lokasi_gudang" class="form-control"
                                   value="{{ old('lokasi_gudang') }}" placeholder="Rak A-1, Gudang B, dll...">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3"
                                      placeholder="Keterangan tambahan...">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex gap-2 justify-content-end">
                    <a href="{{ route('master.barang.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectKategori = document.getElementById('select-kategori');
        const selectSatuan = document.getElementById('select-satuan');

        let lastKategoriVal = selectKategori.value;
        let lastSatuanVal = selectSatuan.value;

        selectKategori.addEventListener('change', function() {
            if (this.value === 'new_option') {
                Swal.fire({
                    title: 'Tambah Kategori Baru',
                    text: 'Masukkan nama kategori baru:',
                    input: 'text',
                    inputPlaceholder: 'Contoh: Alat Kesehatan',
                    showCancelButton: true,
                    confirmButtonText: 'Tambah',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#00288E',
                    cancelButtonColor: '#6b7280',
                    inputValidator: (value) => {
                        if (!value || value.trim() === '') {
                            return 'Nama kategori tidak boleh kosong!';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const newKategori = result.value.trim();
                        
                        let optionExists = false;
                        for (let i = 0; i < selectKategori.options.length; i++) {
                            if (selectKategori.options[i].value.toLowerCase() === newKategori.toLowerCase()) {
                                selectKategori.selectedIndex = i;
                                optionExists = true;
                                break;
                            }
                        }

                        if (!optionExists) {
                            const newOpt = document.createElement('option');
                            newOpt.value = newKategori;
                            newOpt.textContent = newKategori;
                            selectKategori.insertBefore(newOpt, selectKategori.options[selectKategori.options.length - 1]);
                            selectKategori.value = newKategori;
                        }
                        lastKategoriVal = selectKategori.value;
                    } else {
                        selectKategori.value = lastKategoriVal;
                    }
                });
            } else {
                lastKategoriVal = this.value;
            }
        });

        selectSatuan.addEventListener('change', function() {
            if (this.value === 'new_option') {
                Swal.fire({
                    title: 'Tambah Satuan Baru',
                    text: 'Masukkan nama satuan baru:',
                    input: 'text',
                    inputPlaceholder: 'Contoh: Galon',
                    showCancelButton: true,
                    confirmButtonText: 'Tambah',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#00288E',
                    cancelButtonColor: '#6b7280',
                    inputValidator: (value) => {
                        if (!value || value.trim() === '') {
                            return 'Nama satuan tidak boleh kosong!';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const newSatuan = result.value.trim();
                        
                        let optionExists = false;
                        for (let i = 0; i < selectSatuan.options.length; i++) {
                            if (selectSatuan.options[i].value.toLowerCase() === newSatuan.toLowerCase()) {
                                selectSatuan.selectedIndex = i;
                                optionExists = true;
                                break;
                            }
                        }

                        if (!optionExists) {
                            const newOpt = document.createElement('option');
                            newOpt.value = newSatuan;
                            newOpt.textContent = newSatuan;
                            selectSatuan.insertBefore(newOpt, selectSatuan.options[selectSatuan.options.length - 1]);
                            selectSatuan.value = newSatuan;
                        }
                        lastSatuanVal = selectSatuan.value;
                    } else {
                        selectSatuan.value = lastSatuanVal;
                    }
                });
            } else {
                lastSatuanVal = this.value;
            }
        });
    });
</script>
@endpush
@endsection
