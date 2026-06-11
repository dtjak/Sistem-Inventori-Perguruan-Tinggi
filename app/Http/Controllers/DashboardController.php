<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StoreRequisition;
use App\Models\DeliveryRequisition;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseOrder;
use App\Models\ReceivingReport;
use App\Models\Retur;
use App\Models\Aset;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('staff_inventori')) {
            return $this->staffInventoriDashboard();
        } elseif ($user->hasRole('head_inventori')) {
            return $this->headInventoriDashboard();
        } elseif ($user->hasRole(['staff_purchasing', 'head_purchasing'])) {
            return $this->purchasingDashboard();
        } elseif ($user->hasRole('finance')) {
            return $this->financeDashboard();
        } elseif ($user->hasRole('staff_unit')) {
            return $this->staffUnitDashboard();
        } elseif ($user->hasRole('head_unit')) {
            return $this->headUnitDashboard();
        } elseif ($user->hasRole('supplier')) {
            return $this->supplierDashboard();
        }

        return view('dashboard.default');
    }

    private function staffInventoriDashboard()
    {
        $data = [
            'totalBarang' => Barang::count(),
            'totalAset' => Aset::count(),
            'totalSupplier' => Supplier::count(),
            'totalSR' => StoreRequisition::count(),
            'totalDR' => DeliveryRequisition::count(),
            'totalPR' => PurchaseRequisition::count(),
            'totalPO' => PurchaseOrder::count(),
            'totalRR' => ReceivingReport::count(),
            'totalRetur' => Retur::count(),
            'stokMenipis' => Barang::whereColumn('stok_saat_ini', '<=', 'stok_minimum')->count(),
            'srMenunggu' => StoreRequisition::where('status', 'disetujui')->count(),
            'drMenunggu' => DeliveryRequisition::where('status', 'menunggu_approval')->count(),
            'rrMenunggu' => ReceivingReport::where('status', 'menunggu_approval')->count(),
            'barangChart' => Barang::orderBy('stok_saat_ini', 'desc')->take(10)->get(['nama_barang', 'stok_saat_ini']),
            'notifikasi' => auth()->user()->unreadNotifications->take(5),
        ];

        return view('dashboard.staff_inventori', $data);
    }

    private function headInventoriDashboard()
    {
        $data = [
            'drMenunggu' => DeliveryRequisition::where('status', 'menunggu_approval')->with('storeRequisition')->latest()->get(),
            'prMenunggu' => PurchaseRequisition::where('status', 'menunggu_approval')->with('dibuatOleh')->latest()->get(),
            'rrMenunggu' => ReceivingReport::where('status', 'menunggu_approval')->with('penerima')->latest()->get(),
            'returMenunggu' => Retur::where('status', 'menunggu_approval')->with('dibuatOleh')->latest()->get(),
            'notifikasi' => auth()->user()->unreadNotifications->take(5),
        ];

        return view('dashboard.head_inventori', $data);
    }

    private function purchasingDashboard()
    {
        $data = [
            'prApproved' => PurchaseRequisition::where('status', 'approved')
                ->doesntHave('purchaseOrders')->with('dibuatOleh')->latest()->get(),
            'poMenunggu' => PurchaseOrder::where('status', 'menunggu_head_purchasing')
                ->orWhere('status', 'menunggu_finance')->latest()->get(),
            'totalPO' => PurchaseOrder::count(),
            'notifikasi' => auth()->user()->unreadNotifications->take(5),
        ];

        return view('dashboard.purchasing', $data);
    }

    private function financeDashboard()
    {
        $data = [
            'poMenungguVerifikasi' => PurchaseOrder::where('status', 'menunggu_finance')
                ->with(['supplier', 'dibuatOleh'])->latest()->get(),
            'notifikasi' => auth()->user()->unreadNotifications->take(5),
        ];

        return view('dashboard.finance', $data);
    }

    private function staffUnitDashboard()
    {
        $data = [
            'srSaya' => StoreRequisition::where('pemohon_id', auth()->id())->latest()->take(10)->get(),
            'notifikasi' => auth()->user()->unreadNotifications->take(5),
        ];

        return view('dashboard.staff_unit', $data);
    }

    private function headUnitDashboard()
    {
        $data = [
            'srMenunggu' => StoreRequisition::where('status', 'menunggu_approval')
                ->with('pemohon')->latest()->get(),
            'notifikasi' => auth()->user()->unreadNotifications->take(5),
        ];

        return view('dashboard.head_unit', $data);
    }

    private function supplierDashboard()
    {
        $data = [
            'poList' => PurchaseOrder::where('status', 'approved')->latest()->get(),
            'notifikasi' => auth()->user()->unreadNotifications->take(5),
        ];

        return view('dashboard.supplier', $data);
    }

    public function markNotificationRead(Request $request)
    {
        auth()->user()->notifications()->where('id', $request->id)->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}
