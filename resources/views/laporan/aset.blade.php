@extends('layouts.app')
@section('title', 'Laporan Aset')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}" class="text-decoration-none">Laporan</a></li>
    <li class="breadcrumb-item active">Aset</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Laporan Master Aset</h4>
        <p class="text-muted mb-0">Daftar aset perguruan tinggi lengkap dengan kondisi saat ini</p>
    </div>
    <div>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
        </a>
    </div>
</div>

<div class="card data-card">
    <div class="card-header bg-white py-3">
        <form action="{{ route('laporan.aset') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="kondisi" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Kondisi --</option>
                    <option value="baik" {{ request('kondisi') === 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ request('kondisi') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ request('kondisi') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                    <option value="tidak_layak" {{ request('kondisi') === 'tidak_layak' ? 'selected' : '' }}>Tidak Layak</option>
                </select>
            </div>
            @if(request('kondisi'))
                <div class="col-md-2">
                    <a href="{{ route('laporan.aset') }}" class="btn btn-light w-100">Reset</a>
                </div>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase font-size-11">
                <tr>
                    <th class="ps-4">No</th>
                    <th>Kode Aset</th>
                    <th>Nama Aset</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Kondisi</th>
                    <th class="text-end pe-4">Nilai Perolehan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                    <tr>
                        <td class="ps-4">{{ $index + 1 }}</td>
                        <td><span class="badge bg-light text-dark font-monospace">{{ $item->kode_aset }}</span></td>
                        <td class="fw-semibold">{{ $item->nama_aset }}</td>
                        <td>{{ $item->kategori_aset }}</td>
                        <td>{{ $item->lokasi ?: '-' }}</td>
                        <td>
                            @if($item->kondisi === 'baik')
                                <span class="badge bg-success-subtle text-success border px-2 py-1">Baik</span>
                            @elseif($item->kondisi === 'rusak_ringan')
                                <span class="badge bg-warning-subtle text-warning border px-2 py-1">Rusak Ringan</span>
                            @elseif($item->kondisi === 'rusak_berat')
                                <span class="badge bg-danger-subtle text-danger border px-2 py-1">Rusak Berat</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">Tidak Layak</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">Rp {{ number_format($item->nilai_perolehan ?: 0, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            Tidak ada data aset untuk kriteria ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
