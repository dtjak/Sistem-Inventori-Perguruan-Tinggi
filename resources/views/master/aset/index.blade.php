@extends('layouts.app')
@section('title', 'Master Aset')
@section('breadcrumb')
    <li class="breadcrumb-item active">Master Aset</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Master Aset</h4>
        <p class="text-muted mb-0">Kelola master data aset perguruan tinggi</p>
    </div>
    <div class="d-flex gap-2">
        @can('aset.export')
            <a href="{{ route('master.aset.export.excel') }}" class="btn btn-outline-success">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
            <a href="{{ route('master.aset.export.pdf') }}" class="btn btn-outline-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
            </a>
        @endcan
        @can('aset.create')
            <a href="{{ route('master.aset.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Tambah Aset
            </a>
        @endcan
    </div>
</div>

<div class="card data-card">
    <div class="card-header bg-white py-3">
        <form action="{{ route('master.aset.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" 
                           placeholder="Cari nama atau kode aset..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="kondisi" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Kondisi --</option>
                    <option value="baik" {{ request('kondisi') === 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ request('kondisi') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ request('kondisi') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                    <option value="tidak_layak" {{ request('kondisi') === 'tidak_layak' ? 'selected' : '' }}>Tidak Layak</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
            @if(request('search') || request('kondisi'))
                <div class="col-md-2">
                    <a href="{{ route('master.aset.index') }}" class="btn btn-light w-100">
                        Reset
                    </a>
                </div>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase font-size-11">
                <tr>
                    <th class="ps-4" style="width: 5%">No</th>
                    <th style="width: 12%">Kode Aset</th>
                    <th style="width: 25%">Nama Aset</th>
                    <th style="width: 15%">Kategori</th>
                    <th style="width: 15%">Lokasi</th>
                    <th style="width: 13%">Kondisi</th>
                    <th class="text-end pe-4" style="width: 15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($asets as $index => $aset)
                    <tr>
                        <td class="ps-4">{{ $asets->firstItem() + $index }}</td>
                        <td><span class="badge bg-light text-dark font-monospace">{{ $aset->kode_aset }}</span></td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $aset->nama_aset }}</div>
                        </td>
                        <td>{{ $aset->kategori_aset }}</td>
                        <td>{{ $aset->lokasi ?: '-' }}</td>
                        <td>
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
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('master.aset.show', $aset->id) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('aset.edit')
                                    <a href="{{ route('master.aset.edit', $aset->id) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endcan
                                @can('aset.delete')
                                    <button type="button" class="btn btn-outline-danger btn-delete" 
                                            data-id="{{ $aset->id }}" 
                                            data-nama="{{ $aset->nama_aset }}" 
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endcan
                            </div>
                            @can('aset.delete')
                                <form id="delete-form-{{ $aset->id }}" action="{{ route('master.aset.destroy', $aset->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            Tidak ada data aset ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($asets->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $asets->links() }}
        </div>
    @endif
</div>

@can('aset.delete')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                
                Swal.fire({
                    title: 'Hapus Aset?',
                    text: `Apakah Anda yakin ingin menghapus aset "${nama}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${id}`).submit();
                    }
                });
            });
        });
    });
</script>
@endcan
@endsection
