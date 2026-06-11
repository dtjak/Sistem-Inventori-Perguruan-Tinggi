@extends('layouts.app')
@section('title', 'Detail SR - ' . $sr->nomor_sr)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sr.index') }}" class="text-decoration-none">Store Requisition</a></li>
    <li class="breadcrumb-item active">{{ $sr->nomor_sr }}</li>
@endsection

@section('content')
<div class="row g-4">
    <!-- Document -->
    <div class="col-lg-8">
        <div class="card data-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold">{{ $sr->nomor_sr }}</h5>
                    <div class="mt-1">{!! $sr->status_badge !!}</div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('sr.cetak', $sr->id) }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                        <i class="bi bi-printer me-1"></i>Cetak PDF
                    </a>
                    @if(in_array($sr->status, ['draft', 'revisi']))
                    @can('sr.edit')
                    <a href="{{ route('sr.edit', $sr->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    @endcan
                    @endif
                </div>
            </div>
            <div class="card-body">
                <!-- Header Info -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="bg-light rounded p-3">
                            <div class="text-muted small mb-1">Tanggal</div>
                            <div class="fw-semibold">{{ $sr->tanggal->format('d F Y') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light rounded p-3">
                            <div class="text-muted small mb-1">Unit Peminjam</div>
                            <div class="fw-semibold">{{ $sr->unit_peminjam }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light rounded p-3">
                            <div class="text-muted small mb-1">Pemohon</div>
                            <div class="fw-semibold">{{ $sr->pemohon?->name }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light rounded p-3">
                            <div class="text-muted small mb-1">Disetujui Oleh</div>
                            <div class="fw-semibold">{{ $sr->approvedBy?->name ?? '-' }}</div>
                            @if($sr->approved_at)
                            <div class="text-muted small">{{ $sr->approved_at->format('d/m/Y H:i') }}</div>
                            @endif
                        </div>
                    </div>
                    @if($sr->catatan)
                    <div class="col-12">
                        <div class="alert alert-info mb-0 small">
                            <i class="bi bi-chat-text me-1"></i><strong>Catatan:</strong> {{ $sr->catatan }}
                        </div>
                    </div>
                    @endif
                    @if($sr->alasan_penolakan)
                    <div class="col-12">
                        <div class="alert alert-danger mb-0 small">
                            <i class="bi bi-x-circle me-1"></i><strong>Alasan Penolakan:</strong> {{ $sr->alasan_penolakan }}
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Detail Permohonan -->
                <h6 class="fw-bold mb-3">Detail Permohonan (Barang / Aset)</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tipe</th>
                                <th>Kode</th>
                                <th>Nama Item</th>
                                <th class="text-center">Qty</th>
                                <th>Satuan / Lokasi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sr->details as $i => $detail)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    @if($detail->aset_id)
                                        <span class="badge bg-info-subtle text-info border border-info-subtle">Aset</span>
                                    @else
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Barang</span>
                                    @endif
                                </td>
                                <td><code>{{ $detail->aset_id ? $detail->aset?->kode_aset : $detail->barang?->kode_barang }}</code></td>
                                <td class="fw-semibold">{{ $detail->aset_id ? $detail->aset?->nama_aset : $detail->barang?->nama_barang }}</td>
                                <td class="text-center fw-bold">{{ $detail->qty }}</td>
                                <td>{{ $detail->aset_id ? ($detail->aset?->lokasi ?? '-') : ($detail->barang?->satuan ?? '-') }}</td>
                                <td class="text-muted small">{{ $detail->keterangan ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="col-lg-4">
        <!-- Workflow -->
        <div class="card data-card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-diagram-3 me-2"></i>Workflow</h6>
            </div>
            <div class="card-body">
                <!-- Timeline -->
                <div class="d-flex flex-column gap-2">
                    @php
                        $steps = [
                            ['status' => 'draft', 'label' => 'Draft Dibuat', 'icon' => 'pencil'],
                            ['status' => 'menunggu_approval', 'label' => 'Menunggu Approval', 'icon' => 'hourglass'],
                            ['status' => 'disetujui', 'label' => 'Disetujui', 'icon' => 'check-circle'],
                        ];
                        $statusOrder = ['draft' => 0, 'menunggu_approval' => 1, 'revisi' => 1, 'ditolak' => 1, 'disetujui' => 2];
                        $currentOrder = $statusOrder[$sr->status] ?? 0;
                    @endphp
                    @foreach($steps as $step)
                    @php $stepOrder = $statusOrder[$step['status']] ?? 0; @endphp
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0
                            {{ $currentOrder >= $stepOrder ? 'bg-primary text-white' : 'bg-light text-muted' }}"
                            style="width:32px;height:32px;font-size:0.85rem;">
                            <i class="bi bi-{{ $step['icon'] }}"></i>
                        </div>
                        <span class="small {{ $currentOrder >= $stepOrder ? 'fw-semibold' : 'text-muted' }}">{{ $step['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="card data-card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-lightning me-2"></i>Aksi</h6>
            </div>
            <div class="card-body d-flex flex-column gap-2">

                @if(in_array($sr->status, ['draft', 'revisi']))
                @can('sr.create')
                <form action="{{ route('sr.submit', $sr->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-send me-1"></i>Ajukan untuk Approval
                    </button>
                </form>
                @endcan
                @endif

                @if($sr->status === 'menunggu_approval')
                @can('sr.approve')
                <!-- Approve -->
                <form action="{{ route('sr.approve', $sr->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="catatan" id="catatanApprove">
                    <button type="submit" class="btn btn-success w-100 btn-approve">
                        <i class="bi bi-check-circle me-1"></i>Setujui
                    </button>
                </form>

                <!-- Revisi -->
                <button class="btn btn-outline-warning w-100" data-bs-toggle="modal" data-bs-target="#revisiModal">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Kembalikan untuk Revisi
                </button>

                <!-- Tolak -->
                <button class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#tolakModal">
                    <i class="bi bi-x-circle me-1"></i>Tolak
                </button>
                @endcan
                @endif

                @if($sr->status === 'disetujui')
                @can('dr.create')
                <a href="{{ route('dr.create') }}?sr_id={{ $sr->id }}" class="btn btn-success w-100">
                    <i class="bi bi-box-arrow-right me-1"></i>Buat Delivery Requisition
                </a>
                @endcan
                @endif

            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div class="modal fade" id="tolakModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger"><i class="bi bi-x-circle me-2"></i>Tolak Store Requisition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('sr.reject', $sr->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_penolakan" class="form-control" rows="4"
                                  placeholder="Tuliskan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak SR</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Revisi -->
<div class="modal fade" id="revisiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-warning"><i class="bi bi-arrow-counterclockwise me-2"></i>Revisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('sr.revisi', $sr->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan Revisi <span class="text-danger">*</span></label>
                        <textarea name="catatan" class="form-control" rows="4"
                                  placeholder="Apa yang perlu diperbaiki..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Kirim Revisi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
