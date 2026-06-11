<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Aset;
use App\Models\Supplier;
use App\Models\StoreRequisition;
use App\Models\DeliveryRequisition;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseOrder;
use App\Models\ReceivingReport;
use App\Models\Retur;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LaporanBarangExport;
use App\Exports\LaporanSRExport;
use App\Exports\LaporanPOExport;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:laporan.view');
    }

    public function index()
    {
        return view('laporan.index');
    }

    public function barang(Request $request)
    {
        $query = Barang::query();
        $this->applyDateFilter($query, $request);
        if ($request->kategori) $query->where('kategori', $request->kategori);
        if ($request->status) $query->where('status', $request->status);
        $data = $query->get();
        $kategoris = Barang::distinct()->pluck('kategori');

        if ($request->export === 'excel') {
            return Excel::download(new LaporanBarangExport($request->all()), 'laporan-barang-' . date('Ymd') . '.xlsx');
        }
        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('exports.pdf.laporan_barang', compact('data'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-barang-' . date('Ymd') . '.pdf');
        }

        return view('laporan.barang', compact('data', 'kategoris'));
    }

    public function aset(Request $request)
    {
        $query = Aset::query();
        if ($request->kondisi) $query->where('kondisi', $request->kondisi);
        $data = $query->get();

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('exports.pdf.laporan_aset', compact('data'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-aset-' . date('Ymd') . '.pdf');
        }

        return view('laporan.aset', compact('data'));
    }

    public function supplier(Request $request)
    {
        $data = Supplier::all();
        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('exports.pdf.laporan_supplier', compact('data'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-supplier-' . date('Ymd') . '.pdf');
        }
        return view('laporan.supplier', compact('data'));
    }

    public function sr(Request $request)
    {
        $query = StoreRequisition::with(['pemohon', 'approvedBy']);
        $this->applyDateFilter($query, $request);
        if ($request->status) $query->where('status', $request->status);
        $data = $query->latest()->get();

        if ($request->export === 'excel') {
            return Excel::download(new LaporanSRExport($request->all()), 'laporan-sr-' . date('Ymd') . '.xlsx');
        }
        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('exports.pdf.laporan_sr', compact('data'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-sr-' . date('Ymd') . '.pdf');
        }

        return view('laporan.sr', compact('data'));
    }

    public function dr(Request $request)
    {
        $query = DeliveryRequisition::with(['storeRequisition', 'dibuatOleh']);
        $this->applyDateFilter($query, $request);
        if ($request->status) $query->where('status', $request->status);
        $data = $query->latest()->get();

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('exports.pdf.laporan_dr', compact('data'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-dr-' . date('Ymd') . '.pdf');
        }

        return view('laporan.dr', compact('data'));
    }

    public function pr(Request $request)
    {
        $query = PurchaseRequisition::with(['dibuatOleh', 'approvedBy']);
        $this->applyDateFilter($query, $request);
        if ($request->status) $query->where('status', $request->status);
        $data = $query->latest()->get();

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('exports.pdf.laporan_pr', compact('data'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-pr-' . date('Ymd') . '.pdf');
        }

        return view('laporan.pr', compact('data'));
    }

    public function po(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'dibuatOleh']);
        $this->applyDateFilter($query, $request);
        if ($request->status) $query->where('status', $request->status);
        $data = $query->latest()->get();

        if ($request->export === 'excel') {
            return Excel::download(new LaporanPOExport($request->all()), 'laporan-po-' . date('Ymd') . '.xlsx');
        }
        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('exports.pdf.laporan_po', compact('data'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-po-' . date('Ymd') . '.pdf');
        }

        return view('laporan.po', compact('data'));
    }

    public function rr(Request $request)
    {
        $query = ReceivingReport::with(['purchaseOrder.supplier', 'penerima']);
        $this->applyDateFilter($query, $request);
        if ($request->status) $query->where('status', $request->status);
        $data = $query->latest()->get();

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('exports.pdf.laporan_rr', compact('data'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-rr-' . date('Ymd') . '.pdf');
        }

        return view('laporan.rr', compact('data'));
    }

    public function retur(Request $request)
    {
        $query = Retur::with(['supplier', 'dibuatOleh']);
        $this->applyDateFilter($query, $request);
        if ($request->status) $query->where('status', $request->status);
        $data = $query->latest()->get();

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('exports.pdf.laporan_retur', compact('data'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-retur-' . date('Ymd') . '.pdf');
        }

        return view('laporan.retur', compact('data'));
    }

    public function opname(Request $request)
    {
        $query = StockOpname::with(['petugas', 'details.barang']);
        $this->applyDateFilter($query, $request);
        $data = $query->latest()->get();

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('exports.pdf.laporan_opname', compact('data'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-opname-' . date('Ymd') . '.pdf');
        }

        return view('laporan.opname', compact('data'));
    }

    private function applyDateFilter($query, Request $request): void
    {
        if ($request->filter === 'harian') {
            $query->whereDate('tanggal', today());
        } elseif ($request->filter === 'bulanan') {
            $query->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year);
        } elseif ($request->filter === 'tahunan') {
            $query->whereYear('tanggal', now()->year);
        } elseif ($request->filter === 'rentang') {
            if ($request->dari) $query->whereDate('tanggal', '>=', $request->dari);
            if ($request->sampai) $query->whereDate('tanggal', '<=', $request->sampai);
        }
    }
}
