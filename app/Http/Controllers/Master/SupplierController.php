<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:supplier.view')->only(['index', 'show']);
        $this->middleware('permission:supplier.create')->only(['create', 'store']);
        $this->middleware('permission:supplier.edit')->only(['edit', 'update', 'updateRating']);
        $this->middleware('permission:supplier.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Supplier::query();

        // Jika user adalah supplier, hanya boleh melihat datanya sendiri
        if (auth()->user()->hasRole('supplier')) {
            $query->where('email', auth()->user()->email);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama_supplier', 'like', "%{$request->search}%")
                  ->orWhere('kode_supplier', 'like', "%{$request->search}%");
            });
        }
        if ($request->status) $query->where('status', $request->status);

        $suppliers = $query->latest()->paginate(15)->withQueryString();
        return view('master.supplier.index', compact('suppliers'));
    }

    public function create()
    {
        $kode = Supplier::generateKode();
        return view('master.supplier.create', compact('kode'));
    }

    public function store(SupplierRequest $request)
    {
        $data = $request->validated();
        $data['kode_supplier'] = Supplier::generateKode();
        $supplier = Supplier::create($data);
        return redirect()->route('master.supplier.index')
            ->with('success', "Supplier {$supplier->nama_supplier} berhasil ditambahkan.");
    }

    public function show(Supplier $supplier)
    {
        if (auth()->user()->hasRole('supplier')) {
            abort_if($supplier->email !== auth()->user()->email, 403, 'Anda tidak memiliki akses ke data supplier ini.');
        }
        $supplier->load(['purchaseOrders' => fn($q) => $q->latest()->take(10)]);
        return view('master.supplier.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        if (auth()->user()->hasRole('supplier')) {
            abort_if($supplier->email !== auth()->user()->email, 403, 'Anda tidak memiliki akses ke data supplier ini.');
        }
        return view('master.supplier.edit', compact('supplier'));
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        if (auth()->user()->hasRole('supplier')) {
            abort_if($supplier->email !== auth()->user()->email, 403, 'Anda tidak memiliki akses ke data supplier ini.');
        }
        $supplier->update($request->validated());
        return redirect()->route('master.supplier.index')
            ->with('success', "Supplier {$supplier->nama_supplier} berhasil diperbarui.");
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('master.supplier.index')
            ->with('success', "Supplier {$supplier->nama_supplier} berhasil dihapus.");
    }

    public function updateRating(Request $request, Supplier $supplier)
    {
        $request->validate(['rating' => 'required|numeric|min:0|max:5']);
        $supplier->update(['rating' => $request->rating]);
        return response()->json(['success' => true, 'rating' => $supplier->rating]);
    }
}
