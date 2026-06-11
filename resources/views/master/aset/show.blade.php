@extends('layouts.app')
@section('title', 'Detail Aset - ' . $aset->nama_aset)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('master.aset.index') }}" class="text-decoration-none">Master Aset</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card data-card mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle text-info me-2"></i>Detail Aset</h5>
                <span class="badge bg-light text-dark font-monospace fs-6">{{ $aset->kode_aset }}</span>
            </div>
            <div class="card-body">
                <table class="table table-borderless align-middle mb-0">
                    <tbody>
                        <tr>
                            <th style="width: 30%" class="text-muted">Nama Aset</th>
                            <td>: <span class="fw-semibold text-dark">{{ $aset->nama_aset }}</span></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Kategori Aset</th>
                            <td>: {{ $aset->kategori_aset }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Lokasi Aset</th>
                            <td>: {{ $aset->lokasi ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Kondisi</th>
                            <td>: 
                                @if($aset->kondisi === 'baik')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Baik</span>
                                @elseif($aset->kondisi === 'rusak_ringan')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">Rusak Ringan</span>
                                @elseif($aset->kondisi === 'rusak_berat')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Rusak Berat</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Tidak Layak</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Tanggal Perolehan</th>
                            <td>: {{ $aset->tanggal_perolehan ? $aset->tanggal_perolehan->format('d M Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Nilai Perolehan</th>
                            <td>: Rp {{ $aset->nilai_perolehan ? number_format($aset->nilai_perolehan, 0, ',', '.') : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Umur Manfaat</th>
                            <td>: {{ $aset->umur_manfaat ? $aset->umur_manfaat . ' Tahun' : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Keterangan</th>
                            <td>: 
                                <div class="bg-light p-3 rounded mt-1 text-secondary" style="white-space: pre-wrap;">{{ $aset->keterangan ?: 'Tidak ada keterangan.' }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white d-flex gap-2 justify-content-end py-3">
                <a href="{{ route('master.aset.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                @can('aset.edit')
                    <a href="{{ route('master.aset.edit', $aset->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i>Edit Aset
                    </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
