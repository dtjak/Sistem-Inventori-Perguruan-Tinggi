<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\AsetRequest;
use App\Models\Aset;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AsetExport;

class AsetController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:aset.view')->only(['index', 'show']);
        $this->middleware('permission:aset.create')->only(['create', 'store']);
        $this->middleware('permission:aset.edit')->only(['edit', 'update']);
        $this->middleware('permission:aset.delete')->only(['destroy']);
        $this->middleware('permission:aset.export')->only(['export', 'exportPdf']);
    }

    private function generateKode(): string
    {
        $last = Aset::withTrashed()->orderBy('id', 'desc')->first();
        $num = $last ? ((int) substr($last->kode_aset, 4)) + 1 : 1;
        return 'AST-' . str_pad($num, 3, '0', STR_PAD_LEFT);
    }

    public function index(Request $request)
    {
        $query = Aset::query();
        if ($request->search) {
            $query->where('nama_aset', 'like', "%{$request->search}%")
                  ->orWhere('kode_aset', 'like', "%{$request->search}%");
        }
        if ($request->kondisi) $query->where('kondisi', $request->kondisi);

        $asets = $query->latest()->paginate(15)->withQueryString();
        return view('master.aset.index', compact('asets'));
    }

    public function create()
    {
        $kode = $this->generateKode();
        return view('master.aset.create', compact('kode'));
    }

    public function store(AsetRequest $request)
    {
        $data = $request->validated();
        $data['kode_aset'] = $this->generateKode();
        $aset = Aset::create($data);
        return redirect()->route('master.aset.index')
            ->with('success', "Aset {$aset->nama_aset} berhasil ditambahkan.");
    }

    public function show(Aset $aset)
    {
        return view('master.aset.show', compact('aset'));
    }

    public function edit(Aset $aset)
    {
        return view('master.aset.edit', compact('aset'));
    }

    public function update(AsetRequest $request, Aset $aset)
    {
        $aset->update($request->validated());
        return redirect()->route('master.aset.index')
            ->with('success', "Aset {$aset->nama_aset} berhasil diperbarui.");
    }

    public function destroy(Aset $aset)
    {
        $aset->delete();
        return redirect()->route('master.aset.index')
            ->with('success', "Aset {$aset->nama_aset} berhasil dihapus.");
    }

    public function export()
    {
        return Excel::download(new AsetExport, 'aset-' . date('Ymd') . '.xlsx');
    }

    public function exportPdf()
    {
        $asets = Aset::orderBy('nama_aset')->get();
        $pdf = Pdf::loadView('exports.pdf.aset', compact('asets'));
        return $pdf->download('aset-' . date('Ymd') . '.pdf');
    }
}
