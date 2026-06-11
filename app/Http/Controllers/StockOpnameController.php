<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StockOpnameController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:opname.view')->only(['index', 'show']);
        $this->middleware('permission:opname.create')->only(['create', 'store']);
        $this->middleware('permission:opname.edit')->only(['edit', 'update']);
        $this->middleware('permission:opname.delete')->only(['destroy']);
    }

    private function generateNomor(): string
    {
        $year = date('Y');
        $month = date('m');
        $count = StockOpname::whereYear('created_at', $year)->whereMonth('created_at', $month)->count() + 1;
        return "OPN/{$year}/{$month}/" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function index(Request $request)
    {
        $opnames = StockOpname::with('petugas')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->paginate(15)->withQueryString();
        return view('opname.index', compact('opnames'));
    }

    public function create()
    {
        $barangs = Barang::where('status', 'aktif')->orderBy('nama_barang')->get();
        return view('opname.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'details' => 'required|array|min:1',
            'details.*.barang_id' => 'required|exists:barangs,id',
            'details.*.stok_sistem' => 'required|integer|min:0',
            'details.*.stok_fisik' => 'required|integer|min:0',
        ]);

        $opname = DB::transaction(function () use ($request) {
            $opname = StockOpname::create([
                'nomor_opname' => $this->generateNomor(),
                'tanggal' => $request->tanggal,
                'petugas_id' => auth()->id(),
                'catatan' => $request->catatan,
                'status' => 'draft',
            ]);

            foreach (collect($request->details)->filter(fn($d) => !empty($d['barang_id'])) as $detail) {
                $opname->details()->create([
                    'barang_id' => $detail['barang_id'],
                    'stok_sistem' => $detail['stok_sistem'],
                    'stok_fisik' => $detail['stok_fisik'],
                    'keterangan' => $detail['keterangan'] ?? null,
                ]);
            }

            return $opname;
        });

        return redirect()->route('opname.show', $opname->id)
            ->with('success', "Stock Opname {$opname->nomor_opname} berhasil dibuat.");
    }

    public function show(StockOpname $opname)
    {
        $opname->load(['petugas', 'details.barang']);
        return view('opname.show', compact('opname'));
    }

    public function edit(StockOpname $opname)
    {
        abort_if($opname->status !== 'draft', 403);
        $opname->load('details.barang');
        $barangs = Barang::where('status', 'aktif')->orderBy('nama_barang')->get();
        return view('opname.edit', compact('opname', 'barangs'));
    }

    public function update(Request $request, StockOpname $opname)
    {
        abort_if($opname->status !== 'draft', 403);
        $opname->update(['tanggal' => $request->tanggal, 'catatan' => $request->catatan]);
        $opname->details()->delete();
        foreach (collect($request->details)->filter(fn($d) => !empty($d['barang_id'])) as $detail) {
            $opname->details()->create($detail);
        }
        return redirect()->route('opname.show', $opname->id)->with('success', "Stock Opname diperbarui.");
    }

    public function destroy(StockOpname $opname)
    {
        abort_if($opname->status !== 'draft', 403);
        $opname->delete();
        return redirect()->route('opname.index')->with('success', "Stock Opname dihapus.");
    }

    public function selesai(StockOpname $opname)
    {
        abort_if($opname->status !== 'draft', 403);

        DB::transaction(function () use ($opname) {
            // Update stok sistem berdasarkan hasil fisik
            foreach ($opname->details as $detail) {
                Barang::find($detail->barang_id)->update(['stok_saat_ini' => $detail->stok_fisik]);
            }
            $opname->update(['status' => 'selesai']);
        });

        return redirect()->route('opname.show', $opname->id)
            ->with('success', "Stock Opname selesai. Stok telah diperbarui.");
    }

    public function cetak(StockOpname $opname)
    {
        $opname->load(['petugas', 'details.barang']);
        $pdf = Pdf::loadView('exports.pdf.opname', compact('opname'))->setPaper('a4', 'landscape');
        return $pdf->download("Opname-{$opname->nomor_opname}.pdf");
    }
}
