@extends('layouts.app')
@section('title', 'Detail Supplier - ' . $supplier->nama_supplier)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('master.supplier.index') }}" class="text-decoration-none">Master Supplier</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card data-card mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle text-info me-2"></i>Detail Supplier</h5>
                <span class="badge bg-light text-dark font-monospace fs-6">{{ $supplier->kode_supplier }}</span>
            </div>
            <div class="card-body">
                <table class="table table-borderless align-middle mb-0">
                    <tbody>
                        <tr>
                            <th style="width: 30%" class="text-muted">Nama Supplier</th>
                            <td>: <span class="fw-semibold text-dark">{{ $supplier->nama_supplier }}</span></td>
                        </tr>
                        <tr>
                            <th class="text-muted">PIC / Hubungan Kontak</th>
                            <td>: {{ $supplier->pic ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Telepon</th>
                            <td>: {{ $supplier->telepon ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Email</th>
                            <td>: {{ $supplier->email ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Status</th>
                            <td>: 
                                @if($supplier->status === 'aktif')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Aktif</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Non-Aktif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Rating Pelayanan</th>
                            <td>: 
                                <div class="d-inline-flex align-items-center gap-1">
                                    <div class="text-warning fs-5" id="rating-stars">
                                        @php $rating = round($supplier->rating ?: 0); @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star-fill cursor-pointer rating-star" data-value="{{ $i }}" style="cursor: pointer;"></i>
                                        @endfor
                                    </div>
                                    <span class="text-muted small ms-1" id="rating-text">({{ number_format($supplier->rating ?: 0, 1) }})</span>
                                </div>
                                <div class="text-muted font-size-11 mt-1"><i class="bi bi-info-circle me-1"></i>Klik pada bintang untuk memperbarui rating supplier.</div>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Alamat</th>
                            <td>: 
                                <div class="bg-light p-3 rounded mt-1 text-secondary" style="white-space: pre-wrap;">{{ $supplier->alamat ?: 'Tidak ada alamat.' }}</div>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Keterangan</th>
                            <td>: 
                                <div class="bg-light p-3 rounded mt-1 text-secondary" style="white-space: pre-wrap;">{{ $supplier->keterangan ?: 'Tidak ada keterangan.' }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white d-flex gap-2 justify-content-end py-3">
                <a href="{{ route('master.supplier.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                @can('supplier.edit')
                    <a href="{{ route('master.supplier.edit', $supplier->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i>Edit Supplier
                    </a>
                @endcan
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.rating-star');
        const supplierId = "{{ $supplier->id }}";
        const currentRating = parseFloat("{{ $supplier->rating ?: 0 }}");
        
        function updateStarDisplay(ratingVal) {
            stars.forEach((star, idx) => {
                if (idx < ratingVal) {
                    star.classList.remove('bi-star');
                    star.classList.add('bi-star-fill');
                } else {
                    star.classList.remove('bi-star-fill');
                    star.classList.add('bi-star');
                }
            });
        }
        
        updateStarDisplay(Math.round(currentRating));

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-value');
                
                Swal.fire({
                    title: 'Update Rating?',
                    text: `Apakah Anda ingin memberikan rating ${rating} bintang untuk supplier ini?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Update',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ url('master/supplier') }}/${supplierId}/rating`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ rating: rating })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                updateStarDisplay(Math.round(data.rating));
                                document.getElementById('rating-text').innerText = `(${parseFloat(data.rating).toFixed(1)})`;
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: 'Rating supplier berhasil diperbarui.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        })
                        .catch(err => {
                            Swal.fire('Error', 'Gagal memperbarui rating.', 'error');
                        });
                    }
                });
            });
        });
    });
</script>
@endsection
