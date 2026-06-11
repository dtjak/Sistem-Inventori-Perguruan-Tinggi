@extends('layouts.app')
@section('title', 'Edit Purchase Order - ' . $po->nomor_po)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('po.index') }}" class="text-decoration-none">Purchase Order</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card data-card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Purchase Order (PO)</h5>
                <span class="text-muted small">Nomor PO: <span class="fw-semibold text-dark">{{ $po->nomor_po }}</span></span>
            </div>
            <form action="{{ route('po.update', $po->id) }}" method="POST" id="po-form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Supplier Rekanan <span class="text-danger">*</span></label>
                            <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $po->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->nama_supplier }} (PIC: {{ $supplier->pic ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Tanggal PO <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                                   value="{{ old('tanggal', $po->tanggal->format('Y-m-d')) }}" required>
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Estimasi Tanggal Kirim</label>
                            <input type="date" name="tanggal_kirim" class="form-control @error('tanggal_kirim') is-invalid @enderror" 
                                   value="{{ old('tanggal_kirim', $po->tanggal_kirim ? $po->tanggal_kirim->format('Y-m-d') : '') }}">
                            @error('tanggal_kirim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Catatan Pembelian / Syarat & Ketentuan</label>
                            <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                                      rows="2">{{ old('catatan', $po->catatan) }}</textarea>
                            @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                        <h5 class="fw-bold mb-0"><i class="bi bi-box me-2 text-secondary"></i>Item Barang dalam PO</h5>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btn-add-item">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Item
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="items-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40%">Nama Barang</th>
                                    <th class="text-center" style="width: 20%">Quantity</th>
                                    <th class="text-center" style="width: 25%">Harga Satuan (Rp) <span class="text-danger">*</span></th>
                                    <th class="text-center" style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($po->details as $index => $detail)
                                    <tr class="item-row">
                                        <td>
                                            <select name="details[{{ $index }}][barang_id]" class="form-select select-barang" required>
                                                <option value="">-- Pilih Barang --</option>
                                                @foreach($barangs as $barang)
                                                    <option value="{{ $barang->id }}" data-satuan="{{ $barang->satuan }}" 
                                                        {{ $detail->barang_id == $barang->id ? 'selected' : '' }}>
                                                        {{ $barang->nama_barang }} ({{ $barang->kode_barang }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" name="details[{{ $index }}][qty]" class="form-control text-center" min="1" 
                                                       value="{{ old('details.'.$index.'.qty', $detail->qty) }}" required>
                                                <span class="input-group-text label-satuan">{{ $detail->barang->satuan }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" name="details[{{ $index }}][harga]" class="form-control text-end" min="0" 
                                                   value="{{ old('details.'.$index.'.harga', $detail->harga) }}" required>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-white d-flex gap-2 justify-content-end py-3">
                    <a href="{{ route('po.show', $po->id) }}" class="btn btn-secondary">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let rowCount = parseInt("{{ count($po->details) }}");
        const itemsTable = document.getElementById('items-table').getElementsByTagName('tbody')[0];
        const btnAddItem = document.getElementById('btn-add-item');

        function updateRemoveButtons() {
            const rows = itemsTable.getElementsByClassName('item-row');
            const removeButtons = itemsTable.getElementsByClassName('btn-remove-item');
            for (let i = 0; i < removeButtons.length; i++) {
                removeButtons[i].disabled = rows.length <= 1;
            }
        }

        function bindRowEvents(row) {
            const select = row.querySelector('.select-barang');
            const label = row.querySelector('.label-satuan');
            
            select.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const satuan = selectedOption.getAttribute('data-satuan');
                label.innerText = satuan ? satuan : 'satuan';
            });

            const removeBtn = row.querySelector('.btn-remove-item');
            removeBtn.addEventListener('click', function() {
                row.remove();
                updateRemoveButtons();
            });
        }

        itemsTable.querySelectorAll('.item-row').forEach(row => {
            bindRowEvents(row);
        });
        updateRemoveButtons();

        btnAddItem.addEventListener('click', function() {
            const originalRow = itemsTable.querySelector('.item-row');
            const newRow = originalRow.cloneNode(true);
            
            newRow.querySelector('.select-barang').value = '';
            newRow.querySelector('input[type="number"]').value = '1';
            newRow.querySelector('.label-satuan').innerText = 'satuan';
            newRow.querySelectorAll('input').forEach(input => {
                if (input.name.includes('[harga]')) input.value = '';
            });

            newRow.querySelector('.select-barang').name = `details[${rowCount}][barang_id]`;
            newRow.querySelector('input[name$="[qty]"]').name = `details[${rowCount}][qty]`;
            newRow.querySelector('input[name$="[harga]"]').name = `details[${rowCount}][harga]`;

            itemsTable.appendChild(newRow);
            bindRowEvents(newRow);
            rowCount++;
            updateRemoveButtons();
        });
    });
</script>
@endsection
