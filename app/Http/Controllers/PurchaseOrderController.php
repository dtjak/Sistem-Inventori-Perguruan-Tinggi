<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use App\Models\Supplier;
use App\Services\PurchaseOrderService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseOrderController extends Controller
{
    public function __construct(protected PurchaseOrderService $service)
    {
        $this->middleware('permission:po.view')->only(['index', 'show']);
        $this->middleware('permission:po.create')->only(['create', 'store']);
        $this->middleware('permission:po.edit')->only(['edit', 'update']);
        $this->middleware('permission:po.delete')->only(['destroy']);
        $this->middleware('permission:po.approve_head')->only(['approveHead', 'rejectHead']);
        $this->middleware('permission:po.approve_finance')->only(['approveFinance', 'rejectFinance']);
    }

    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'dibuatOleh']);

        if (auth()->user()->hasRole('supplier')) {
            $query->where('status', 'approved');
        }

        if ($request->status) $query->where('status', $request->status);
        if ($request->search) $query->where('nomor_po', 'like', "%{$request->search}%");

        $pos = $query->latest()->paginate(15)->withQueryString();
        return view('po.index', compact('pos'));
    }

    public function riwayat(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'dibuatOleh'])->where('status', 'dikirim');

        if ($request->search) $query->where('nomor_po', 'like', "%{$request->search}%");

        $pos = $query->latest()->paginate(15)->withQueryString();
        $isRiwayat = true;
        return view('po.index', compact('pos', 'isRiwayat'));
    }

    public function create(Request $request)
    {
        $prId = $request->pr_id;
        $pr = $prId ? PurchaseRequisition::with('details.barang')->find($prId) : null;
        $suppliers = Supplier::where('status', 'aktif')->orderBy('nama_supplier')->get();
        $barangs = Barang::where('status', 'aktif')->orderBy('nama_barang')->get();
        return view('po.create', compact('pr', 'suppliers', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
            'tanggal_kirim' => 'nullable|date|after_or_equal:tanggal',
            'details' => 'required|array|min:1',
            'details.*.barang_id' => 'required|exists:barangs,id',
            'details.*.qty' => 'required|integer|min:1',
            'details.*.harga' => 'required|numeric|min:0',
        ]);

        $data = $request->only(['supplier_id', 'tanggal', 'tanggal_kirim', 'catatan', 'purchase_requisition_id']);
        $details = collect($request->details)->filter(fn($d) => !empty($d['barang_id']))->toArray();
        $po = $this->service->create($data, $details);

        return redirect()->route('po.show', $po->id)
            ->with('success', "Purchase Order {$po->nomor_po} berhasil dibuat.");
    }

    public function show(PurchaseOrder $po)
    {
        $po->load(['supplier', 'dibuatOleh', 'details.barang', 'approvedHeadPurchasing', 'approvedFinance', 'receivingReports']);
        return view('po.show', compact('po'));
    }

    public function edit(PurchaseOrder $po)
    {
        abort_if($po->status !== 'draft', 403);
        $suppliers = Supplier::where('status', 'aktif')->orderBy('nama_supplier')->get();
        $barangs = Barang::where('status', 'aktif')->orderBy('nama_barang')->get();
        $po->load('details');
        return view('po.edit', compact('po', 'suppliers', 'barangs'));
    }

    public function update(Request $request, PurchaseOrder $po)
    {
        abort_if($po->status !== 'draft', 403);
        $po->update($request->only(['supplier_id', 'tanggal', 'tanggal_kirim', 'catatan']));
        $po->details()->delete();
        $total = 0;
        foreach (collect($request->details)->filter(fn($d) => !empty($d['barang_id'])) as $detail) {
            $detail['subtotal'] = $detail['qty'] * $detail['harga'];
            $total += $detail['subtotal'];
            $po->details()->create($detail);
        }
        $po->update(['total' => $total]);
        return redirect()->route('po.show', $po->id)->with('success', "PO berhasil diperbarui.");
    }

    public function destroy(PurchaseOrder $po)
    {
        abort_if($po->status !== 'draft', 403);
        $po->delete();
        return redirect()->route('po.index')->with('success', "PO berhasil dihapus.");
    }

    public function submit(PurchaseOrder $po)
    {
        abort_if($po->status !== 'draft', 403);
        $this->service->submit($po);
        return redirect()->route('po.show', $po->id)->with('success', "PO berhasil diajukan ke Head Purchasing.");
    }

    public function approveHead(Request $request, PurchaseOrder $po)
    {
        $this->service->approveHead($po, $request->catatan);
        return redirect()->route('po.show', $po->id)->with('success', "PO diteruskan ke Finance.");
    }

    public function rejectHead(Request $request, PurchaseOrder $po)
    {
        $request->validate(['alasan_penolakan' => 'required|string']);
        $this->service->reject($po, $request->alasan_penolakan);
        return redirect()->route('po.show', $po->id)->with('success', "PO ditolak.");
    }

    public function approveFinance(Request $request, PurchaseOrder $po)
    {
        $this->service->approveFinance($po, $request->catatan);
        return redirect()->route('po.show', $po->id)->with('success', "PO berhasil disetujui Finance.");
    }

    public function rejectFinance(Request $request, PurchaseOrder $po)
    {
        $request->validate(['alasan_penolakan' => 'required|string']);
        $this->service->reject($po, $request->alasan_penolakan);
        return redirect()->route('po.show', $po->id)->with('success', "PO ditolak oleh Finance.");
    }

    public function kirim(Request $request, PurchaseOrder $po)
    {
        abort_if(!auth()->user()->hasRole('supplier') || $po->status !== 'approved', 403);
        
        $request->validate([
            'nomor_resi' => 'required|string|max:100',
            'kurir_ekspedisi' => 'required|string|max:100',
            'catatan_pengiriman' => 'nullable|string',
        ]);

        $po->update([
            'status' => 'dikirim',
            'nomor_resi' => $request->nomor_resi,
            'kurir_ekspedisi' => $request->kurir_ekspedisi,
            'catatan_pengiriman' => $request->catatan_pengiriman,
            'tanggal_pengiriman' => now(),
        ]);

        // Send notification to Staff Inventori
        $staffInventori = \App\Models\User::role('staff_inventori')->get();
        foreach ($staffInventori as $staff) {
            $staff->notify(new \App\Notifications\POShippedNotification($po));
        }

        return redirect()->route('po.show', $po->id)->with('success', 'Barang berhasil dikirim dan informasi pengiriman telah disimpan.');
    }

    public function cetak(PurchaseOrder $po)
    {
        $po->load(['supplier', 'dibuatOleh', 'details.barang', 'approvedHeadPurchasing', 'approvedFinance']);
        $pdf = Pdf::loadView('exports.pdf.po', compact('po'))->setPaper('a4', 'portrait');
        return $pdf->download("PO-{$po->nomor_po}.pdf");
    }
}
