<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PurchaseRequisition;
use App\Services\PurchaseRequisitionService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseRequisitionController extends Controller
{
    public function __construct(protected PurchaseRequisitionService $service)
    {
        $this->middleware('permission:pr.view')->only(['index', 'show']);
        $this->middleware('permission:pr.create')->only(['create', 'store']);
        $this->middleware('permission:pr.edit')->only(['edit', 'update']);
        $this->middleware('permission:pr.delete')->only(['destroy']);
        $this->middleware('permission:pr.approve')->only(['approve', 'reject']);
    }

    public function index(Request $request)
    {
        $query = PurchaseRequisition::with('dibuatOleh');
        if ($request->status) $query->where('status', $request->status);
        if ($request->search) $query->where('nomor_pr', 'like', "%{$request->search}%");
        $prs = $query->latest()->paginate(15)->withQueryString();
        return view('pr.index', compact('prs'));
    }

    public function create()
    {
        $barangs = Barang::where('status', 'aktif')->orderBy('nama_barang')->get();
        return view('pr.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'alasan' => 'required|string',
            'details' => 'required|array|min:1',
            'details.*.barang_id' => 'required|exists:barangs,id',
            'details.*.qty' => 'required|integer|min:1',
            'details.*.estimasi_harga' => 'required|numeric|min:0',
        ]);

        $data = $request->only(['tanggal', 'alasan']);
        $details = collect($request->details)->filter(fn($d) => !empty($d['barang_id']))->toArray();
        $pr = $this->service->create($data, $details);

        return redirect()->route('pr.show', $pr->id)
            ->with('success', "Purchase Requisition {$pr->nomor_pr} berhasil dibuat.");
    }

    public function show(PurchaseRequisition $pr)
    {
        $pr->load(['dibuatOleh', 'details.barang', 'approvedBy', 'purchaseOrders']);
        return view('pr.show', compact('pr'));
    }

    public function edit(PurchaseRequisition $pr)
    {
        abort_if(!in_array($pr->status, ['draft', 'revisi']), 403);
        $barangs = Barang::where('status', 'aktif')->orderBy('nama_barang')->get();
        $pr->load('details');
        return view('pr.edit', compact('pr', 'barangs'));
    }

    public function update(Request $request, PurchaseRequisition $pr)
    {
        abort_if(!in_array($pr->status, ['draft', 'revisi']), 403);
        $data = $request->only(['tanggal', 'alasan']);
        $details = collect($request->details)->filter(fn($d) => !empty($d['barang_id']))->toArray();
        $this->service->update($pr, $data, $details);
        return redirect()->route('pr.show', $pr->id)->with('success', "PR berhasil diperbarui.");
    }

    public function destroy(PurchaseRequisition $pr)
    {
        abort_if($pr->status !== 'draft', 403);
        $pr->delete();
        return redirect()->route('pr.index')->with('success', "PR berhasil dihapus.");
    }

    public function submit(PurchaseRequisition $pr)
    {
        abort_if(!in_array($pr->status, ['draft', 'revisi']), 403);
        $this->service->submit($pr);
        return redirect()->route('pr.show', $pr->id)->with('success', "PR berhasil diajukan.");
    }

    public function approve(Request $request, PurchaseRequisition $pr)
    {
        $this->service->approve($pr, $request->catatan);
        return redirect()->route('pr.show', $pr->id)->with('success', "PR berhasil disetujui.");
    }

    public function reject(Request $request, PurchaseRequisition $pr)
    {
        $request->validate(['alasan_penolakan' => 'required|string']);
        $this->service->reject($pr, $request->alasan_penolakan);
        return redirect()->route('pr.show', $pr->id)->with('success', "PR berhasil ditolak.");
    }

    public function cetak(PurchaseRequisition $pr)
    {
        $pr->load(['dibuatOleh', 'details.barang', 'approvedBy']);
        $pdf = Pdf::loadView('exports.pdf.pr', compact('pr'))->setPaper('a4', 'portrait');
        return $pdf->download("PR-{$pr->nomor_pr}.pdf");
    }
}
