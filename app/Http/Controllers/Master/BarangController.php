<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\BarangRequest;
use App\Models\Barang;
use App\Repositories\BarangRepository;
use App\Services\BarangService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BarangExport;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    public function __construct(
        protected BarangService $service,
        protected BarangRepository $repository
    ) {
        $this->middleware('permission:barang.view')->only(['index', 'show']);
        $this->middleware('permission:barang.create')->only(['create', 'store']);
        $this->middleware('permission:barang.edit')->only(['edit', 'update']);
        $this->middleware('permission:barang.delete')->only(['destroy']);
        $this->middleware('permission:barang.export')->only(['export', 'exportPdf']);
        $this->middleware('permission:barang.import')->only(['import']);
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'kategori', 'status']);
        $barangs = $this->repository->paginate(15, $filters);
        $kategoris = $this->repository->getKategori();

        return view('master.barang.index', compact('barangs', 'kategoris', 'filters'));
    }

    public function create()
    {
        $kode = $this->service->generateKode();
        $defaultKategori = ['ATK', 'Elektronik', 'Furniture', 'Kebersihan', 'Peralatan'];
        $kategoris = array_values(array_unique(array_merge($defaultKategori, Barang::distinct()->whereNotNull('kategori')->pluck('kategori')->toArray())));
        
        $defaultSatuan = ['Pcs', 'Rim', 'Lusin', 'Pack', 'Set', 'Unit', 'Botol', 'Box'];
        $satuans = array_values(array_unique(array_merge($defaultSatuan, Barang::distinct()->whereNotNull('satuan')->pluck('satuan')->toArray())));

        return view('master.barang.create', compact('kode', 'kategoris', 'satuans'));
    }

    public function store(BarangRequest $request)
    {
        $barang = $this->service->create($request->validated());
        return redirect()->route('master.barang.index')
            ->with('success', "Barang {$barang->nama_barang} berhasil ditambahkan.");
    }

    public function show(Barang $barang)
    {
        $barang->load(['storeRequisitionDetails.storeRequisition', 'receivingReportDetails.receivingReport']);
        return view('master.barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        $defaultKategori = ['ATK', 'Elektronik', 'Furniture', 'Kebersihan', 'Peralatan'];
        $kategoris = array_values(array_unique(array_merge($defaultKategori, Barang::distinct()->whereNotNull('kategori')->pluck('kategori')->toArray())));
        
        $defaultSatuan = ['Pcs', 'Rim', 'Lusin', 'Pack', 'Set', 'Unit', 'Botol', 'Box'];
        $satuans = array_values(array_unique(array_merge($defaultSatuan, Barang::distinct()->whereNotNull('satuan')->pluck('satuan')->toArray())));

        return view('master.barang.edit', compact('barang', 'kategoris', 'satuans'));
    }

    public function update(BarangRequest $request, Barang $barang)
    {
        $this->service->update($barang, $request->validated());
        return redirect()->route('master.barang.index')
            ->with('success', "Barang {$barang->nama_barang} berhasil diperbarui.");
    }

    public function destroy(Barang $barang)
    {
        $this->service->delete($barang);
        return redirect()->route('master.barang.index')
            ->with('success', "Barang {$barang->nama_barang} berhasil dihapus.");
    }

    public function export()
    {
        return Excel::download(new BarangExport, 'barang-' . date('Ymd') . '.xlsx');
    }

    public function exportPdf()
    {
        $barangs = Barang::orderBy('nama_barang')->get();
        $pdf = Pdf::loadView('exports.pdf.barang', compact('barangs'));
        return $pdf->download('barang-' . date('Ymd') . '.pdf');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        $this->service->import($request->file('file'));
        return redirect()->route('master.barang.index')
            ->with('success', 'Import barang berhasil.');
    }
}
