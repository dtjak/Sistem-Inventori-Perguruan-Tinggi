<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PurchaseOrder;
use App\Models\ReceivingReport;
use App\Services\ReceivingReportService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceivingReportController extends Controller
{
    public function __construct(protected ReceivingReportService $service)
    {
        $this->middleware('permission:rr.view')->only(['index', 'show']);
        $this->middleware('permission:rr.create')->only(['create', 'store']);
        $this->middleware('permission:rr.edit')->only(['edit', 'update']);
        $this->middleware('permission:rr.delete')->only(['destroy']);
        $this->middleware('permission:rr.approve')->only(['approve', 'reject']);
    }

    public function index(Request $request)
    {
        $query = ReceivingReport::with(['purchaseOrder.supplier', 'penerima']);
        if ($request->status) $query->where('status', $request->status);
        if ($request->search) $query->where('nomor_rr', 'like', "%{$request->search}%");
        $rrs = $query->latest()->paginate(15)->withQueryString();
        return view('rr.index', compact('rrs'));
    }

    public function create(Request $request)
    {
        $poId = $request->po_id;
        $po = PurchaseOrder::with('details.barang')->findOrFail($poId);
        abort_if(!in_array($po->status, ['approved', 'dikirim']), 403, 'PO harus berstatus approved atau dikirim.');
        return view('rr.create', compact('po'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'tanggal_terima' => 'required|date',
            'details' => 'required|array|min:1',
            'details.*.barang_id' => 'required|exists:barangs,id',
            'details.*.qty_dipesan' => 'required|integer|min:0',
            'details.*.qty_diterima' => 'required|integer|min:0',
            'details.*.kondisi' => 'required|in:baik,rusak,kurang',
        ]);

        $data = $request->only(['purchase_order_id', 'tanggal_terima', 'catatan']);
        $details = collect($request->details)->filter(fn($d) => !empty($d['barang_id']))->toArray();
        $rr = $this->service->create($data, $details);

        return redirect()->route('rr.show', $rr->id)
            ->with('success', "Receiving Report {$rr->nomor_rr} berhasil dibuat.");
    }

    public function show(ReceivingReport $rr)
    {
        $rr->load(['purchaseOrder.supplier', 'penerima', 'details.barang', 'approvedBy']);
        return view('rr.show', compact('rr'));
    }

    public function edit(ReceivingReport $rr)
    {
        abort_if($rr->status !== 'draft', 403);
        $rr->load('details');
        return view('rr.edit', compact('rr'));
    }

    public function update(Request $request, ReceivingReport $rr)
    {
        abort_if($rr->status !== 'draft', 403);
        $rr->update($request->only(['tanggal_terima', 'catatan']));
        $rr->details()->delete();
        foreach (collect($request->details)->filter(fn($d) => !empty($d['barang_id'])) as $detail) {
            $rr->details()->create($detail);
        }
        return redirect()->route('rr.show', $rr->id)->with('success', "RR berhasil diperbarui.");
    }

    public function destroy(ReceivingReport $rr)
    {
        abort_if($rr->status !== 'draft', 403);
        $rr->delete();
        return redirect()->route('rr.index')->with('success', "RR berhasil dihapus.");
    }

    public function submit(ReceivingReport $rr)
    {
        abort_if($rr->status !== 'draft', 403);
        $this->service->submit($rr);
        return redirect()->route('rr.show', $rr->id)->with('success', "RR berhasil diajukan.");
    }

    public function approve(Request $request, ReceivingReport $rr)
    {
        $this->service->approve($rr, $request->catatan);
        return redirect()->route('rr.show', $rr->id)->with('success', "RR disetujui. Stok telah diperbarui.");
    }

    public function reject(Request $request, ReceivingReport $rr)
    {
        $request->validate(['alasan_penolakan' => 'required|string']);
        $this->service->reject($rr, $request->alasan_penolakan);
        return redirect()->route('rr.show', $rr->id)->with('success', "RR ditolak.");
    }

    public function cetak(ReceivingReport $rr)
    {
        $rr->load(['purchaseOrder.supplier', 'penerima', 'details.barang', 'approvedBy']);
        $pdf = Pdf::loadView('exports.pdf.rr', compact('rr'))->setPaper('a4', 'portrait');
        return $pdf->download("RR-{$rr->nomor_rr}.pdf");
    }
}
