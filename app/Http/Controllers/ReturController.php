<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Retur;
use App\Models\ReceivingReport;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Services\ReturService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReturController extends Controller
{
    public function __construct(protected ReturService $service)
    {
        $this->middleware('permission:retur.view')->only(['index', 'show']);
        $this->middleware('permission:retur.create')->only(['create', 'store']);
        $this->middleware('permission:retur.edit')->only(['edit', 'update']);
        $this->middleware('permission:retur.delete')->only(['destroy']);
        $this->middleware('permission:retur.approve')->only(['approve', 'reject']);
    }

    public function index(Request $request)
    {
        $query = Retur::with(['supplier', 'dibuatOleh', 'receivingReport']);
        if ($request->status) $query->where('status', $request->status);
        if ($request->search) $query->where('nomor_retur', 'like', "%{$request->search}%");
        $returs = $query->latest()->paginate(15)->withQueryString();
        return view('retur.index', compact('returs'));
    }

    public function create(Request $request)
    {
        $rrId = $request->rr_id;
        $poId = $request->po_id;
        
        $rr = null;
        $po = null;
        
        if ($rrId) {
            $rr = ReceivingReport::with('details.barang', 'purchaseOrder.supplier')->findOrFail($rrId);
        } elseif ($poId) {
            $po = PurchaseOrder::with('details.barang', 'supplier')->findOrFail($poId);
        } else {
            abort(404, 'Reference document not found.');
        }

        $suppliers = Supplier::where('status', 'aktif')->get();
        return view('retur.create', compact('rr', 'po', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiving_report_id' => 'nullable|exists:receiving_reports,id',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
            'alasan' => 'required|string',
            'details' => 'required|array|min:1',
            'details.*.barang_id' => 'required|exists:barangs,id',
            'details.*.qty' => 'required|integer|min:1',
        ]);

        $data = $request->only(['receiving_report_id', 'purchase_order_id', 'supplier_id', 'tanggal', 'alasan', 'catatan']);
        $details = collect($request->details)->filter(fn($d) => !empty($d['barang_id']))->toArray();
        $retur = $this->service->create($data, $details);

        return redirect()->route('retur.show', $retur->id)
            ->with('success', "Retur {$retur->nomor_retur} berhasil dibuat.");
    }

    public function show(Retur $retur)
    {
        $retur->load(['supplier', 'dibuatOleh', 'approvedBy', 'details.barang', 'receivingReport', 'purchaseOrder']);
        return view('retur.show', compact('retur'));
    }

    public function edit(Retur $retur)
    {
        abort_if(!in_array($retur->status, ['draft']), 403);
        $retur->load('details');
        $suppliers = Supplier::where('status', 'aktif')->get();
        return view('retur.edit', compact('retur', 'suppliers'));
    }

    public function update(Request $request, Retur $retur)
    {
        abort_if($retur->status !== 'draft', 403);
        $retur->update($request->only(['supplier_id', 'tanggal', 'alasan', 'catatan']));
        $retur->details()->delete();
        foreach (collect($request->details)->filter(fn($d) => !empty($d['barang_id'])) as $detail) {
            $retur->details()->create($detail);
        }
        return redirect()->route('retur.show', $retur->id)->with('success', "Retur berhasil diperbarui.");
    }

    public function destroy(Retur $retur)
    {
        abort_if($retur->status !== 'draft', 403);
        $retur->delete();
        return redirect()->route('retur.index')->with('success', "Retur berhasil dihapus.");
    }

    public function submit(Retur $retur)
    {
        abort_if($retur->status !== 'draft', 403);
        $this->service->submit($retur);
        return redirect()->route('retur.show', $retur->id)->with('success', "Retur berhasil diajukan.");
    }

    public function approve(Request $request, Retur $retur)
    {
        $this->service->approve($retur, $request->catatan);
        return redirect()->route('retur.show', $retur->id)->with('success', "Retur berhasil disetujui.");
    }

    public function reject(Request $request, Retur $retur)
    {
        $request->validate(['alasan_penolakan' => 'required|string']);
        $this->service->reject($retur, $request->alasan_penolakan);
        return redirect()->route('retur.show', $retur->id)->with('success', "Retur ditolak.");
    }

    public function kirim(Request $request, Retur $retur)
    {
        abort_if(!auth()->user()->hasRole('supplier') || $retur->status !== 'approved', 403);
        
        $request->validate([
            'nomor_resi' => 'required|string|max:100',
            'kurir_ekspedisi' => 'required|string|max:100',
            'catatan_pengiriman' => 'nullable|string',
        ]);

        $retur->update([
            'status' => 'dikirim',
            'nomor_resi' => $request->nomor_resi,
            'kurir_ekspedisi' => $request->kurir_ekspedisi,
            'catatan_pengiriman' => $request->catatan_pengiriman,
            'tanggal_pengiriman' => now(),
        ]);

        // Send notification to Staff Inventori
        $staffInventori = \App\Models\User::role('staff_inventori')->get();
        foreach ($staffInventori as $staff) {
            $staff->notify(new \App\Notifications\ApprovalRequestedNotification($retur, 'Retur Barang (Barang Pengganti dikirim oleh Supplier)'));
        }

        return redirect()->route('retur.show', $retur->id)->with('success', 'Barang pengganti berhasil dikirim dan informasi pengiriman telah disimpan.');
    }

    public function selesai(Retur $retur)
    {
        $this->service->selesai($retur);
        return redirect()->route('retur.show', $retur->id)->with('success', "Retur selesai diproses.");
    }

    public function cetak(Retur $retur)
    {
        $retur->load(['supplier', 'dibuatOleh', 'approvedBy', 'details.barang']);
        $pdf = Pdf::loadView('exports.pdf.retur', compact('retur'))->setPaper('a4', 'portrait');
        return $pdf->download("Retur-{$retur->nomor_retur}.pdf");
    }
}
