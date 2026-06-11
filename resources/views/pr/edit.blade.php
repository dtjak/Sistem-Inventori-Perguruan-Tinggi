@extends('layouts.app')
@section('title', 'Edit Purchase Requisition - ' . $pr->nomor_pr)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pr.index') }}" class="text-decoration-none">Purchase Requisition</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card data-card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Purchase Requisition (PR)</h5>
                <span class="text-muted small">Nomor PR: <span class="fw-semibold text-dark">{{ $pr->nomor_pr }}</span></span>
            </div>
            <form action="{{ route('pr.update', $pr->id) }}" method="POST" id="pr-form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Pengajuan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                                   value="{{ old('tanggal', $pr->tanggal->format('Y-m-d')) }}" required>
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Alasan Pengajuan / Deskripsi Kebutuhan <span class="text-danger">*</span></label>
                            <input type="text" name="alasan" class="form-control @error('alasan') is-invalid @enderror" 
                                   value="{{ old('alasan', $pr->alasan) }}" required>
                            @error('alasan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                        <h5 class="fw-bold mb-0"><i class="bi bi-box me-2 text-secondary"></i>Item Barang yang Diajukan</h5>
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
                                    <th class="text-center" style="width: 25%">Estimasi Harga Satuan (Rp)</th>
                                    <th class="text-center" style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pr->details as $index => $detail)
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
                                            <input type="number" name="details[{{ $index }}][estimasi_harga]" class="form-control text-end" min="0" 
                                                   value="{{ old('details.'.$index.'.estimasi_harga', $detail->estimasi_harga) }}" required>
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
                    <a href="{{ route('pr.show', $pr->id) }}" class="btn btn-secondary">
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
        let rowCount = parseInt("{{ count($pr->details) }}");
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
            
            // Reset fields
            newRow.querySelector('.select-barang').value = '';
            newRow.querySelector('input[type="number"]').value = '1';
            newRow.querySelector('.label-satuan').innerText = 'satuan';
            newRow.querySelectorAll('input').forEach(input => {
                if (input.name.includes('[estimasi_harga]')) input.value = '';
            });

            // Update names for form array
            newRow.querySelector('.select-barang').name = `details[${rowCount}][barang_id]`;
            newRow.querySelector('input[name$="[qty]"]').name = `details[${rowCount}][qty]`;
            newRow.querySelector('input[name$="[estimasi_harga]"]').name = `details[${rowCount}][estimasi_harga]`;

            itemsTable.appendChild(newRow);
            bindRowEvents(newRow);
            rowCount++;
            updateRemoveButtons();
        });
    });
</script>
@endsection
