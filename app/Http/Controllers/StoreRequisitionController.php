<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequisitionRequest;
use App\Models\Barang;
use App\Models\Aset;
use App\Models\StoreRequisition;
use App\Services\StoreRequisitionService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class StoreRequisitionController extends Controller
{
    public function __construct(protected StoreRequisitionService $service)
    {
        $this->middleware('permission:sr.view')->only(['index', 'show']);
        $this->middleware('permission:sr.create')->only(['create', 'store']);
        $this->middleware('permission:sr.edit')->only(['edit', 'update']);
        $this->middleware('permission:sr.delete')->only(['destroy']);
        $this->middleware('permission:sr.approve')->only(['approve', 'reject']);
    }

    public function index(Request $request)
    {
        $query = StoreRequisition::with(['pemohon', 'approvedBy']);

        // Staff unit only see their own
        if (auth()->user()->hasRole('staff_unit')) {
            $query->where('pemohon_id', auth()->id());
        }

        if ($request->status) $query->where('status', $request->status);
        if ($request->search) {
            $query->where('nomor_sr', 'like', "%{$request->search}%")
                  ->orWhere('unit_peminjam', 'like', "%{$request->search}%");
        }

        $srs = $query->latest()->paginate(15)->withQueryString();
        return view('sr.index', compact('srs'));
    }

    public function create()
    {
        $barangs = Barang::where('status', 'aktif')->orderBy('nama_barang')->get();
        $asets = Aset::whereIn('kondisi', ['baik', 'rusak_ringan'])->orderBy('nama_aset')->get();
        return view('sr.create', compact('barangs', 'asets'));
    }

    public function store(StoreRequisitionRequest $request)
    {
        $data = $request->only(['tanggal', 'unit_peminjam', 'catatan']);
        $details = collect($request->details)->filter(fn($d) => !empty($d['barang_id']) || !empty($d['aset_id']))->toArray();

        $sr = $this->service->create($data, $details);
        return redirect()->route('sr.show', $sr->id)
            ->with('success', "Store Requisition {$sr->nomor_sr} berhasil dibuat.");
    }

    public function show(StoreRequisition $sr)
    {
        $sr->load(['pemohon', 'details.barang', 'details.aset', 'approvedBy']);
        return view('sr.show', compact('sr'));
    }

    public function edit(StoreRequisition $sr)
    {
        abort_if(!in_array($sr->status, ['draft', 'revisi']), 403, 'SR tidak dapat diedit pada status ini.');
        $barangs = Barang::where('status', 'aktif')->orderBy('nama_barang')->get();
        $asets = Aset::whereIn('kondisi', ['baik', 'rusak_ringan'])->orderBy('nama_aset')->get();
        $sr->load('details');
        return view('sr.edit', compact('sr', 'barangs', 'asets'));
    }

    public function update(StoreRequisitionRequest $request, StoreRequisition $sr)
    {
        abort_if(!in_array($sr->status, ['draft', 'revisi']), 403);
        $data = $request->only(['tanggal', 'unit_peminjam', 'catatan']);
        $details = collect($request->details)->filter(fn($d) => !empty($d['barang_id']) || !empty($d['aset_id']))->toArray();
        $this->service->update($sr, $data, $details);
        return redirect()->route('sr.show', $sr->id)
            ->with('success', "Store Requisition {$sr->nomor_sr} berhasil diperbarui.");
    }

    public function destroy(StoreRequisition $sr)
    {
        abort_if($sr->status !== 'draft', 403, 'Hanya SR dengan status draft yang dapat dihapus.');
        $sr->delete();
        return redirect()->route('sr.index')->with('success', "SR berhasil dihapus.");
    }

    public function submit(StoreRequisition $sr)
    {
        abort_if(!in_array($sr->status, ['draft', 'revisi']), 403);
        $this->service->submit($sr);
        return redirect()->route('sr.show', $sr->id)
            ->with('success', "SR berhasil diajukan untuk approval.");
    }

    public function approve(Request $request, StoreRequisition $sr)
    {
        $this->service->approve($sr, $request->catatan);
        return redirect()->route('sr.show', $sr->id)
            ->with('success', "SR berhasil disetujui.");
    }

    public function reject(Request $request, StoreRequisition $sr)
    {
        $request->validate(['alasan_penolakan' => 'required|string']);
        $this->service->reject($sr, $request->alasan_penolakan);
        return redirect()->route('sr.show', $sr->id)
            ->with('success', "SR berhasil ditolak.");
    }

    public function revisi(Request $request, StoreRequisition $sr)
    {
        $request->validate(['catatan' => 'required|string']);
        $this->service->revisi($sr, $request->catatan);
        return redirect()->route('sr.show', $sr->id)
            ->with('success', "SR dikembalikan untuk revisi.");
    }

    public function cetak(StoreRequisition $sr)
    {
        $sr->load(['pemohon', 'details.barang', 'details.aset', 'approvedBy']);
        $pdf = Pdf::loadView('exports.pdf.sr', compact('sr'))->setPaper('a4', 'portrait');
        return $pdf->download("SR-{$sr->nomor_sr}.pdf");
    }
}
