@extends('layouts.app')
@section('title', 'Edit Barang')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('master.barang.index') }}" class="text-decoration-none">Master Barang</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card data-card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil text-primary me-2"></i>Edit Barang: {{ $barang->nama_barang }}</h5>
            </div>
            <form action="{{ route('master.barang.update', $barang->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Barang</label>
                            <input type="text" class="form-control bg-light" value="{{ $barang->kode_barang }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror"
                                   value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                            @error('nama_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" id="select-kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat }}" {{ old('kategori', $barang->kategori) === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                @endforeach
                                <option value="new_option" class="text-primary fw-semibold">+ Tambah Kategori Baru...</option>
                            </select>
                            @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                            <select name="satuan" id="select-satuan" class="form-select @error('satuan') is-invalid @enderror" required>
                                @foreach($satuans as $sat)
                                    <option value="{{ $sat }}" {{ old('satuan', $barang->satuan) === $sat ? 'selected' : '' }}>{{ $sat }}</option>
                                @endforeach
                                <option value="new_option" class="text-primary fw-semibold">+ Tambah Satuan Baru...</option>
                            </select>
                            @error('satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Stok Minimum <span class="text-danger">*</span></label>
                            <input type="number" name="stok_minimum" class="form-control"
                                   value="{{ old('stok_minimum', $barang->stok_minimum) }}" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Stok Saat Ini</label>
                            <input type="number" name="stok_saat_ini" class="form-control"
                                   value="{{ old('stok_saat_ini', $barang->stok_saat_ini) }}" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="aktif" {{ old('status', $barang->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $barang->status) === 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Lokasi Gudang</label>
                            <input type="text" name="lokasi_gudang" class="form-control"
                                   value="{{ old('lokasi_gudang', $barang->lokasi_gudang) }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $barang->keterangan) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex gap-2 justify-content-end">
                    <a href="{{ route('master.barang.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Perbarui
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
                    confirmButtonColor: '#4e73df',
                    cancelButtonColor: '#858796',
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
                    confirmButtonColor: '#4e73df',
                    cancelButtonColor: '#858796',
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
