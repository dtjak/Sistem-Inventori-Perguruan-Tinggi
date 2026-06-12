@extends('layouts.app')

@section('title', 'Dashboard - Staff Inventori')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col">
        <h4 class="fw-bold mb-1">Dashboard Staff Inventori</h4>
        <p class="text-muted mb-0">Selamat datang, {{ auth()->user()->name }}</p>
    </div>
    <div class="col-auto d-flex align-items-center">
        <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('l, d F Y') }}</span>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label mb-1">Total Barang</div>
                        <div class="stat-value text-primary">{{ $totalBarang }}</div>
                    </div>
                    <div class="stat-icon bg-gradient-primary text-white">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
                @if($stokMenipis > 0)
                <div class="mt-2">
                    <span class="badge bg-warning text-dark">{{ $stokMenipis }} stok menipis</span>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label mb-1">Total Aset</div>
                        <div class="stat-value text-success">{{ $totalAset }}</div>
                    </div>
                    <div class="stat-icon bg-gradient-success text-white">
                        <i class="bi bi-laptop"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label mb-1">Total Supplier</div>
                        <div class="stat-value text-warning">{{ $totalSupplier }}</div>
                    </div>
                    <div class="stat-icon bg-gradient-warning text-white">
                        <i class="bi bi-truck"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label mb-1">SR Masuk</div>
                        <div class="stat-value text-info">{{ $srMenunggu }}</div>
                    </div>
                    <div class="stat-icon bg-gradient-info text-white">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                </div>
                <div class="mt-2"><span class="badge bg-info">Perlu Diproses</span></div>
            </div>
        </div>
    </div>
</div>

<!-- Second Row Stats -->
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Total SR', 'value'=>$totalSR, 'icon'=>'file-earmark-text', 'color'=>'secondary'],
        ['label'=>'Total DR', 'value'=>$totalDR, 'icon'=>'box-arrow-right', 'color'=>'primary'],
        ['label'=>'Total PR', 'value'=>$totalPR, 'icon'=>'cart-plus', 'color'=>'success'],
        ['label'=>'Total PO', 'value'=>$totalPO, 'icon'=>'receipt', 'color'=>'warning'],
        ['label'=>'Total RR', 'value'=>$totalRR, 'icon'=>'box-arrow-in-down', 'color'=>'info'],
        ['label'=>'Total Retur', 'value'=>$totalRetur, 'icon'=>'arrow-return-left', 'color'=>'danger'],
    ] as $stat)
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <i class="bi bi-{{ $stat['icon'] }} text-{{ $stat['color'] }} fs-4 mb-2 d-block"></i>
                <div class="fw-bold fs-5">{{ $stat['value'] }}</div>
                <div class="text-muted" style="font-size:0.75rem;">{{ $stat['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4">
    <!-- Grafik Stok -->
    <div class="col-lg-8">
        <div class="card data-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart-fill text-primary me-2"></i>Stok Barang (Top 10)</h6>
            </div>
            <div class="card-body">
                <canvas id="stokChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <!-- Notifikasi & Perlu Perhatian -->
    <div class="col-lg-4">
        <div class="card data-card mb-3">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-exclamation-triangle text-warning me-2"></i>Perlu Perhatian</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="small">DR Menunggu Approval</span>
                        <span class="badge bg-warning text-dark">{{ $drMenunggu }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="small">RR Menunggu Approval</span>
                        <span class="badge bg-warning text-dark">{{ $rrMenunggu }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="small">Stok Menipis</span>
                        <a href="{{ route('master.barang.index') }}?status=aktif" class="badge bg-danger text-decoration-none">{{ $stokMenipis }}</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="small">SR Masuk (Disetujui)</span>
                        <a href="{{ route('sr.index') }}?status=disetujui" class="badge bg-success text-decoration-none">{{ $srMenunggu }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Recent Notifications -->
        <div class="card data-card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-bell text-info me-2"></i>Notifikasi Terbaru</h6>
            </div>
            <div class="card-body p-0" style="max-height:200px;overflow-y:auto;">
                @forelse($notifikasi as $notif)
                <div class="p-3 border-bottom {{ $notif->read_at ? '' : 'bg-light' }}">
                    <div class="fw-semibold" style="font-size:0.8rem;">{{ $notif->data['title'] ?? '-' }}</div>
                    <div class="text-muted" style="font-size:0.75rem;">{{ $notif->created_at->diffForHumans() }}</div>
                </div>
                @empty
                <div class="p-3 text-muted text-center small">Tidak ada notifikasi</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('stokChart').getContext('2d');

// Create gradient for bars
const gradient = ctx.createLinearGradient(0, 0, 0, 400);
gradient.addColorStop(0, 'rgba(0, 40, 142, 0.9)');
gradient.addColorStop(1, 'rgba(26, 79, 196, 0.5)');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($barangChart->pluck('nama_barang')) !!},
        datasets: [{
            label: 'Stok Saat Ini',
            data: {!! json_encode($barangChart->pluck('stok_saat_ini')) !!},
            backgroundColor: gradient,
            borderColor: 'rgba(0, 40, 142, 1)',
            borderWidth: 1,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,40,142,0.06)' },
                ticks: { color: '#6b7280', font: { size: 11 } }
            },
            x: {
                grid: { display: false },
                ticks: { color: '#6b7280', font: { size: 11 } }
            }
        }
    }
});
</script>
@endpush
