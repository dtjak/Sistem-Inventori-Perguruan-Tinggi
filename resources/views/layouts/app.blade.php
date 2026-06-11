<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Inventori PT</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #1a2035;
            --sidebar-hover: #252f49;
            --accent: #4e73df;
            --accent-light: #6f8fef;
            --success: #1cc88a;
            --warning: #f6c23e;
            --danger: #e74a3b;
            --info: #36b9cc;
            --text-muted: #858796;
        }

        * { font-family: 'Inter', sans-serif; }

        body {
            background-color: #f4f6f9;
            color: #333;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            left: 0; top: 0;
            z-index: 1040;
            overflow-y: auto;
            transition: width 0.3s ease;
            box-shadow: 4px 0 15px rgba(0,0,0,0.3);
        }

        .sidebar.collapsed { width: 70px; }
        .sidebar.collapsed .sidebar-brand-text,
        .sidebar.collapsed .nav-label,
        .sidebar.collapsed .menu-text,
        .sidebar.collapsed .sidebar-section-title { display: none !important; }
        .sidebar.collapsed .nav-link { justify-content: center; padding: 12px; }
        .sidebar.collapsed .nav-link .nav-icon { margin-right: 0; }
        .sidebar.collapsed .submenu { display: none !important; }

        .sidebar-brand {
            padding: 1.5rem 1rem;
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: white;
            flex-shrink: 0;
        }

        .sidebar-brand-text {
            color: white;
            font-weight: 700;
            font-size: 1rem;
            line-height: 1.2;
        }

        .sidebar-brand-text small {
            color: rgba(255,255,255,0.5);
            font-size: 0.7rem;
            font-weight: 400;
            display: block;
        }

        .sidebar-section-title {
            color: rgba(255,255,255,0.35);
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 1rem 1rem 0.3rem;
        }

        .nav-link {
            color: rgba(255,255,255,0.65) !important;
            padding: 10px 1rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 6px;
            margin: 2px 8px;
            transition: all 0.2s;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.1) !important;
            color: white !important;
        }

        .nav-link.active { background: linear-gradient(90deg, var(--accent), #5a85f5) !important; }

        .nav-icon { font-size: 1rem; flex-shrink: 0; }

        .submenu { padding-left: 0; }
        .submenu .nav-link {
            padding: 8px 1rem 8px 2.5rem;
            font-size: 0.8rem;
            margin: 1px 8px;
        }

        .submenu-toggle[aria-expanded="true"] .arrow-icon {
            transform: rotate(90deg);
        }

        .arrow-icon { transition: transform 0.2s; margin-left: auto; font-size: 0.7rem; }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        .main-content.expanded { margin-left: 70px; }

        /* Topbar */
        .topbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 0 1.5rem;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .page-content { padding: 1.5rem; }

        /* Cards */
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .stat-card .card-body { padding: 1.5rem; }

        .stat-icon {
            width: 55px; height: 55px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }

        .stat-value { font-size: 1.8rem; font-weight: 700; line-height: 1; }
        .stat-label { font-size: 0.8rem; color: var(--text-muted); font-weight: 500; }

        /* Data Card */
        .data-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        }

        .data-card .card-header {
            background: white;
            border-bottom: 1px solid #f0f0f0;
            padding: 1rem 1.5rem;
            border-radius: 12px 12px 0 0 !important;
        }

        /* Badges */
        .badge { font-weight: 500; padding: 0.4em 0.75em; border-radius: 6px; }

        /* Buttons */
        .btn { border-radius: 8px; font-weight: 500; font-size: 0.85rem; }
        .btn-primary { background: var(--accent); border-color: var(--accent); }
        .btn-primary:hover { background: #3d5dcc; border-color: #3d5dcc; }

        /* Tables */
        .table th { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
        .table td { font-size: 0.875rem; vertical-align: middle; }

        /* Notifications dropdown */
        .notification-bell { position: relative; }
        .notification-badge {
            position: absolute; top: -4px; right: -4px;
            background: var(--danger); color: white;
            border-radius: 50%; width: 18px; height: 18px;
            font-size: 0.65rem; display: flex; align-items: center; justify-content: center;
            font-weight: 700;
        }

        /* Breadcrumb */
        .breadcrumb { font-size: 0.8rem; margin-bottom: 0; }

        /* Form */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1.5px solid #e0e0e0;
            font-size: 0.875rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(78,115,223,0.15);
        }

        /* Alert */
        .alert { border: none; border-radius: 10px; }

        /* Gradient backgrounds */
        .bg-gradient-primary { background: linear-gradient(135deg, #4e73df 0%, #6f8fef 100%); }
        .bg-gradient-success { background: linear-gradient(135deg, #1cc88a 0%, #3ee8a5 100%); }
        .bg-gradient-warning { background: linear-gradient(135deg, #f6c23e 0%, #f8d57e 100%); }
        .bg-gradient-danger { background: linear-gradient(135deg, #e74a3b 0%, #f08070 100%); }
        .bg-gradient-info { background: linear-gradient(135deg, #36b9cc 0%, #5dd4e3 100%); }
        .bg-gradient-secondary { background: linear-gradient(135deg, #858796 0%, #a0a3b1 100%); }

        /* Scrollbar */
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }

        /* Row detail form */
        .detail-row { background: #f8f9ff; border-radius: 8px; padding: 0.75rem; margin-bottom: 0.5rem; border: 1px solid #e8ecff; }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeInUp 0.4s ease; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <i class="bi bi-boxes"></i>
        </div>
        <div class="sidebar-brand-text">
            Inventori PT
            <small>Sistem Inventori</small>
        </div>
    </a>

    <ul class="nav flex-column py-2">

        <li class="sidebar-section-title menu-text">Main</li>

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2 nav-icon"></i>
                <span class="menu-text">Dashboard</span>
            </a>
        </li>

        <!-- Katalog -->
        @hasanyrole('staff_inventori|head_inventori|staff_unit|head_unit')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('katalog.*') ? 'active' : '' }}"
               href="{{ route('katalog.index') }}">
                <i class="bi bi-journal-bookmark nav-icon"></i>
                <span class="menu-text">Katalog Barang & Aset</span>
            </a>
        </li>
        @endhasanyrole

        @canany(['barang.view', 'aset.view', 'supplier.view'])
        <li class="sidebar-section-title menu-text">Master Data</li>

        @can('barang.view')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('master.barang.*') ? 'active' : '' }}"
               href="{{ route('master.barang.index') }}">
                <i class="bi bi-box-seam nav-icon"></i>
                <span class="menu-text">Barang</span>
            </a>
        </li>
        @endcan

        @can('aset.view')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('master.aset.*') ? 'active' : '' }}"
               href="{{ route('master.aset.index') }}">
                <i class="bi bi-laptop nav-icon"></i>
                <span class="menu-text">Aset</span>
            </a>
        </li>
        @endcan

        @can('supplier.view')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('master.supplier.*') ? 'active' : '' }}"
               href="{{ route('master.supplier.index') }}">
                <i class="bi bi-truck nav-icon"></i>
                <span class="menu-text">Supplier</span>
            </a>
        </li>
        @endcan
        @endcanany

        <li class="sidebar-section-title menu-text">Transaksi</li>

        @can('sr.view')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('sr.*') ? 'active' : '' }}"
               href="{{ route('sr.index') }}">
                <i class="bi bi-file-earmark-text nav-icon"></i>
                <span class="menu-text">Store Requisition</span>
            </a>
        </li>
        @endcan

        @can('dr.view')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dr.*') ? 'active' : '' }}"
               href="{{ route('dr.index') }}">
                <i class="bi bi-box-arrow-right nav-icon"></i>
                <span class="menu-text">Delivery Requisition</span>
            </a>
        </li>
        @endcan

        @can('pr.view')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pr.*') ? 'active' : '' }}"
               href="{{ route('pr.index') }}">
                <i class="bi bi-cart-plus nav-icon"></i>
                <span class="menu-text">Purchase Requisition</span>
            </a>
        </li>
        @endcan

        @can('po.view')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('po.index') || request()->routeIs('po.show') || request()->routeIs('po.create') || request()->routeIs('po.edit') ? 'active' : '' }}"
               href="{{ route('po.index') }}">
                <i class="bi bi-receipt nav-icon"></i>
                <span class="menu-text">Purchase Order</span>
            </a>
        </li>
        @if(auth()->user()->hasRole('supplier') || auth()->user()->hasAnyRole(['staff_purchasing', 'head_purchasing', 'staff_inventori', 'head_inventori']))
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('po.riwayat') ? 'active' : '' }}"
               href="{{ route('po.riwayat') }}">
                <i class="bi bi-clock-history nav-icon"></i>
                <span class="menu-text">Riwayat PO</span>
            </a>
        </li>
        @endif
        @endcan

        @can('rr.view')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('rr.*') ? 'active' : '' }}"
               href="{{ route('rr.index') }}">
                <i class="bi bi-box-arrow-in-down nav-icon"></i>
                <span class="menu-text">Receiving Report</span>
            </a>
        </li>
        @endcan

        @can('retur.view')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('retur.*') ? 'active' : '' }}"
               href="{{ route('retur.index') }}">
                <i class="bi bi-arrow-return-left nav-icon"></i>
                <span class="menu-text">Retur Barang</span>
            </a>
        </li>
        @endcan

        @can('opname.view')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('opname.*') ? 'active' : '' }}"
               href="{{ route('opname.index') }}">
                <i class="bi bi-clipboard-check nav-icon"></i>
                <span class="menu-text">Stock Opname</span>
            </a>
        </li>
        @endcan

        @can('laporan.view')
        <li class="sidebar-section-title menu-text">Laporan</li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}"
               href="{{ route('laporan.index') }}">
                <i class="bi bi-bar-chart-line nav-icon"></i>
                <span class="menu-text">Laporan</span>
            </a>
        </li>
        @endcan

    </ul>
</nav>

<!-- Main Content -->
<div class="main-content" id="mainContent">

    <!-- Topbar -->
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-light" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>

        <div class="d-flex align-items-center gap-3">
            <!-- Notifications -->
            <div class="dropdown notification-bell">
                <button class="btn btn-light btn-sm position-relative" data-bs-toggle="dropdown">
                    <i class="bi bi-bell fs-5"></i>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="notification-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" style="width:320px; max-height:400px; overflow-y:auto;">
                    <li class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2">
                        <span class="fw-bold">Notifikasi</span>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.markAllRead') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link btn-sm p-0 text-primary">Tandai semua dibaca</button>
                        </form>
                        @endif
                    </li>
                    <li><hr class="dropdown-divider m-0"></li>
                    @forelse(auth()->user()->notifications->take(8) as $notification)
                    <li>
                        <a class="dropdown-item py-2 px-3 {{ $notification->read_at ? '' : 'bg-light' }}"
                           href="{{ $notification->data['url'] ?? '#' }}">
                            <div class="d-flex gap-2">
                                <i class="bi bi-bell-fill text-primary mt-1 flex-shrink-0"></i>
                                <div>
                                    <div class="fw-semibold" style="font-size:0.8rem;">{{ $notification->data['title'] ?? '' }}</div>
                                    <div class="text-muted" style="font-size:0.75rem;">{{ $notification->data['message'] ?? '' }}</div>
                                    <div class="text-muted" style="font-size:0.7rem;">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </a>
                    </li>
                    @empty
                    <li class="dropdown-item text-muted text-center py-3">Tidak ada notifikasi</li>
                    @endforelse
                </ul>
            </div>

            <!-- User menu -->
            <div class="dropdown">
                <button class="btn btn-light btn-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white"
                         style="width:32px;height:32px;font-size:0.8rem;font-weight:600;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="d-none d-md-block text-start">
                        <div style="font-size:0.8rem;font-weight:600;">{{ auth()->user()->name }}</div>
                        <div class="text-muted" style="font-size:0.7rem;">{{ auth()->user()->getRoleNames()->first() }}</div>
                    </div>
                    <i class="bi bi-chevron-down text-muted" style="font-size:0.7rem;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="page-content fade-in">

        @yield('content')
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Sidebar Toggle
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('mainContent');
const sidebarToggle = document.getElementById('sidebarToggle');

sidebarToggle?.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
});

// Mobile sidebar
if (window.innerWidth <= 768) {
    sidebarToggle?.addEventListener('click', () => {
        sidebar.classList.toggle('show');
    });
}

// DataTables default init
$(document).ready(function() {
    $('.datatable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        pageLength: 15,
        responsive: true
    });

    // SweetAlert confirm delete
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74a3b',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });

    // SweetAlert confirm approve
    $(document).on('click', '.btn-approve', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        Swal.fire({
            title: 'Konfirmasi Persetujuan',
            text: 'Apakah Anda yakin menyetujui dokumen ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1cc88a',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonColor: '#4e73df',
            confirmButtonText: 'OK'
        });
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Gagal!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonColor: '#e74a3b',
            confirmButtonText: 'OK'
        });
    });
</script>
@endif

@stack('scripts')
</body>
</html>
