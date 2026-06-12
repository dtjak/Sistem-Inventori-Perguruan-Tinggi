<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DeliveryRequisition;
use App\Models\StoreRequisition;
use App\Services\DeliveryRequisitionService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DeliveryRequisitionController extends Controller
{
    public function __construct(protected DeliveryRequisitionService $service)
    {
        $this->middleware('permission:dr.view')->only(['index', 'show']);
        $this->middleware('permission:dr.create')->only(['create', 'store']);
        $this->middleware('permission:dr.edit')->only(['edit', 'update']);
        $this->middleware('permission:dr.delete')->only(['destroy']);
        $this->middleware('permission:dr.approve')->only(['approve', 'reject']);
    }

    public function index(Request $request)
    {
        $query = DeliveryRequisition::with(['storeRequisition', 'dibuatOleh']);
        if ($request->status) $query->where('status', $request->status);
        if ($request->search) {
            $query->where('nomor_dr', 'like', "%{$request->search}%");
        }
        $drs = $query->latest()->paginate(15)->withQueryString();
        return view('dr.index', compact('drs'));
    }

    public function create(Request $request)
    {
        $srId = $request->sr_id;
        $sr = StoreRequisition::with('details.barang')->findOrFail($srId);
        abort_if($sr->status !== 'disetujui', 403, 'SR harus berstatus disetujui untuk membuat DR.');
        $barangs = Barang::where('status', 'aktif')->orderBy('nama_barang')->get();
        return view('dr.create', compact('sr', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_requisition_id' => 'required|exists:store_requisitions,id',
            'tanggal' => 'required|date',
            'details' => 'required|array|min:1',
            'details.*.barang_id' => 'required|exists:barangs,id',
            'details.*.qty_distribusi' => 'required|integer|min:1',
        ]);

        $sr = StoreRequisition::findOrFail($request->store_requisition_id);
        $details = collect($request->details)->filter(fn($d) => !empty($d['barang_id']))->toArray();
        $dr = $this->service->create($sr, $request->only(['tanggal', 'catatan']), $details);

        return redirect()->route('dr.show', $dr->id)
            ->with('success', "Delivery Requisition {$dr->nomor_dr} berhasil dibuat.");
    }

    public function show(DeliveryRequisition $dr)
    {
        $dr->load(['storeRequisition.pemohon', 'details.barang', 'dibuatOleh', 'approvedBy']);
        return view('dr.show', compact('dr'));
    }

    public function edit(DeliveryRequisition $dr)
    {
        abort_if(!in_array($dr->status, ['draft', 'revisi']), 403);
        $barangs = Barang::where('status', 'aktif')->orderBy('nama_barang')->get();
        $dr->load('details');
        return view('dr.edit', compact('dr', 'barangs'));
    }

    public function update(Request $request, DeliveryRequisition $dr)
    {
        abort_if(!in_array($dr->status, ['draft', 'revisi']), 403);
        $dr->update($request->only(['tanggal', 'catatan']));
        $dr->details()->delete();
        foreach (collect($request->details)->filter(fn($d) => !empty($d['barang_id'])) as $detail) {
            $dr->details()->create($detail);
        }
        return redirect()->route('dr.show', $dr->id)->with('success', "DR berhasil diperbarui.");
    }

    public function destroy(DeliveryRequisition $dr)
    {
        abort_if($dr->status !== 'draft', 403);
        $dr->delete();
        return redirect()->route('dr.index')->with('success', "DR berhasil dihapus.");
    }

    public function submit(DeliveryRequisition $dr)
    {
        abort_if(!in_array($dr->status, ['draft', 'revisi']), 403);
        $this->service->submit($dr);
        return redirect()->route('dr.show', $dr->id)->with('success', "DR berhasil diajukan.");
    }

    public function approve(Request $request, DeliveryRequisition $dr)
    {
        $this->service->approve($dr, $request->catatan);
        return redirect()->route('dr.show', $dr->id)->with('success', "DR berhasil disetujui.");
    }

    public function reject(Request $request, DeliveryRequisition $dr)
    {
        $request->validate(['alasan_penolakan' => 'required|string']);
        $this->service->reject($dr, $request->alasan_penolakan);
        return redirect()->route('dr.show', $dr->id)->with('success', "DR berhasil ditolak.");
    }

    public function kirim(DeliveryRequisition $dr)
    {
        abort_if(!auth()->user()->can('dr.create'), 403, 'Anda tidak memiliki akses.');
        abort_if($dr->status !== 'approved', 403, 'DR harus disetujui terlebih dahulu.');

        $this->service->kirim($dr);
        return redirect()->route('dr.show', $dr->id)->with('success', "Barang sedang dikirim.");
    }

    public function selesai(DeliveryRequisition $dr)
    {
        abort_if($dr->status !== 'dikirim', 403, 'DR belum dikirim.');

        $isPemohon = auth()->id() === $dr->storeRequisition->pemohon_id;
        $isStaffInventori = auth()->user()->can('dr.create');
        abort_if(!$isPemohon && !$isStaffInventori, 403, 'Hanya pemohon unit atau staff inventori yang dapat menyelesaikan DR ini.');

        $this->service->selesai($dr);
        return redirect()->route('dr.show', $dr->id)->with('success', "DR ditandai selesai.");
    }

    public function cetak(DeliveryRequisition $dr)
    {
        $dr->load(['storeRequisition.pemohon', 'details.barang', 'dibuatOleh', 'approvedBy']);
        $pdf = Pdf::loadView('exports.pdf.dr', compact('dr'))->setPaper('a4', 'portrait');
        return $pdf->download("DR-{$dr->nomor_dr}.pdf");
    }
}
