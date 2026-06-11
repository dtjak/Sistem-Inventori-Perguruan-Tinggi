@extends('layouts.app')
@section('title', 'Buat Store Requisition')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sr.index') }}" class="text-decoration-none">Store Requisition</a></li>
    <li class="breadcrumb-item active">Buat SR</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10">
        <div class="card data-card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle text-primary me-2"></i>Buat Store Requisition</h5>
            </div>
            <form action="{{ route('sr.store') }}" method="POST" id="srForm">
                @csrf
                <div class="card-body">
                    <!-- Header SR -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                   value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Unit Peminjam <span class="text-danger">*</span></label>
                            <input type="text" name="unit_peminjam" class="form-control @error('unit_peminjam') is-invalid @enderror"
                                   value="{{ old('unit_peminjam', auth()->user()->unit) }}" required>
                            @error('unit_peminjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Pemohon</label>
                            <input type="text" class="form-control bg-light" value="{{ auth()->user()->name }}" readonly>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Catatan</label>
                            <textarea name="catatan" class="form-control" rows="2"
                                      placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                        </div>
                    </div>

                    <!-- Detail Barang & Aset -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0"><i class="bi bi-list-ul me-2"></i>Detail Permohonan (Barang / Aset)</h6>
                        <button type="button" class="btn btn-success btn-sm" id="addRow">
                            <i class="bi bi-plus me-1"></i>Tambah Item
                        </button>
                    </div>

                    <div id="detailContainer">
                        <div class="detail-row row g-2 align-items-center mb-2" data-index="0">
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold mb-1">Tipe <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm type-select">
                                    <option value="barang">Barang Habis Pakai</option>
                                    <option value="aset">Aset / Peralatan</option>
                                </select>
                            </div>
                            <div class="col-md-4 item-barang-col">
                                <label class="form-label small fw-semibold mb-1">Barang <span class="text-danger">*</span></label>
                                <select name="details[0][barang_id]" class="form-select form-select-sm barang-select" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($barangs as $barang)
                                    <option value="{{ $barang->id }}" data-stok="{{ $barang->stok_saat_ini }}" data-satuan="{{ $barang->satuan }}">
                                        {{ $barang->kode_barang }} - {{ $barang->nama_barang }} (Stok: {{ $barang->stok_saat_ini }} {{ $barang->satuan }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 item-aset-col d-none">
                                <label class="form-label small fw-semibold mb-1">Aset <span class="text-danger">*</span></label>
                                <select name="details[0][aset_id]" class="form-select form-select-sm aset-select" disabled>
                                    <option value="">-- Pilih Aset --</option>
                                    @foreach($asets as $aset)
                                    <option value="{{ $aset->id }}" data-lokasi="{{ $aset->lokasi }}">
                                        {{ $aset->kode_aset }} - {{ $aset->nama_aset }} (Kondisi: {{ $aset->kondisi }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold mb-1">Qty <span class="text-danger">*</span></label>
                                <input type="number" name="details[0][qty]" class="form-control form-control-sm" min="1" value="1" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold mb-1">Keterangan</label>
                                <input type="text" name="details[0][keterangan]" class="form-control form-control-sm" placeholder="Keterangan...">
                            </div>
                            <div class="col-md-1 d-flex align-items-end pt-3">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-row w-100">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    @error('details')
                    <div class="alert alert-danger mt-2 small py-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="card-footer bg-white d-flex gap-2 justify-content-end">
                    <a href="{{ route('sr.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Batal
                    </a>
                    <button type="submit" name="action" value="draft" class="btn btn-outline-primary">
                        <i class="bi bi-save me-1"></i>Simpan Draft
                    </button>
                    <button type="submit" name="action" value="submit" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i>Simpan & Ajukan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let rowIndex = 1;
const barangOptions = `@foreach($barangs as $barang)<option value="{{ $barang->id }}" data-stok="{{ $barang->stok_saat_ini }}" data-satuan="{{ $barang->satuan }}">{{ $barang->kode_barang }} - {{ $barang->nama_barang }} (Stok: {{ $barang->stok_saat_ini }} {{ $barang->satuan }})</option>@endforeach`;
const asetOptions = `@foreach($asets as $aset)<option value="{{ $aset->id }}" data-lokasi="{{ $aset->lokasi }}">{{ $aset->kode_aset }} - {{ $aset->nama_aset }} (Kondisi: {{ $aset->kondisi }})</option>@endforeach`;

$('#addRow').on('click', function() {
    const newRow = `
    <div class="detail-row row g-2 align-items-center mb-2" data-index="${rowIndex}">
        <div class="col-md-2">
            <select class="form-select form-select-sm type-select">
                <option value="barang">Barang Habis Pakai</option>
                <option value="aset">Aset / Peralatan</option>
            </select>
        </div>
        <div class="col-md-4 item-barang-col">
            <select name="details[${rowIndex}][barang_id]" class="form-select form-select-sm barang-select" required>
                <option value="">-- Pilih Barang --</option>
                ${barangOptions}
            </select>
        </div>
        <div class="col-md-4 item-aset-col d-none">
            <select name="details[${rowIndex}][aset_id]" class="form-select form-select-sm aset-select" disabled>
                <option value="">-- Pilih Aset --</option>
                ${asetOptions}
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="details[${rowIndex}][qty]" class="form-control form-control-sm" min="1" value="1" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="details[${rowIndex}][keterangan]" class="form-control form-control-sm" placeholder="Keterangan...">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-outline-danger btn-sm remove-row w-100"><i class="bi bi-trash"></i></button>
        </div>
    </div>`;
    $('#detailContainer').append(newRow);
    rowIndex++;
});

$(document).on('change', '.type-select', function() {
    const row = $(this).closest('.detail-row');
    const type = $(this).val();
    if (type === 'barang') {
        row.find('.item-barang-col').removeClass('d-none');
        row.find('.barang-select').removeAttr('disabled').attr('required', true);
        row.find('.item-aset-col').addClass('d-none');
        row.find('.aset-select').attr('disabled', true).val('').removeAttr('required');
    } else {
        row.find('.item-aset-col').removeClass('d-none');
        row.find('.aset-select').removeAttr('disabled').attr('required', true);
        row.find('.item-barang-col').addClass('d-none');
        row.find('.barang-select').attr('disabled', true).val('').removeAttr('required');
    }
});

$(document).on('click', '.remove-row', function() {
    if ($('.detail-row').length > 1) {
        $(this).closest('.detail-row').remove();
    } else {
        Swal.fire('Info', 'Minimal satu item harus ada.', 'info');
    }
});
</script>
@endpush
