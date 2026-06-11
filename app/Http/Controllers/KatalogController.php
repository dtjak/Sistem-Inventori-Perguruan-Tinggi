<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Aset;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->hasAnyRole(['staff_inventori', 'head_inventori', 'staff_unit', 'head_unit'])) {
                abort(403, 'Anda tidak memiliki akses ke halaman Katalog Barang & Aset.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $selectedCategory = $request->input('category');
        
        $barangQuery = Barang::where('status', 'aktif');
        $asetQuery = Aset::whereIn('kondisi', ['baik', 'rusak_ringan']);

        if ($search) {
            $barangQuery->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
            
            $asetQuery->where(function($q) use ($search) {
                $q->where('nama_aset', 'like', "%{$search}%")
                  ->orWhere('kode_aset', 'like', "%{$search}%")
                  ->orWhere('kategori_aset', 'like', "%{$search}%");
            });
        }

        if ($selectedCategory) {
            $barangQuery->where('kategori', $selectedCategory);
            $asetQuery->where('kategori_aset', $selectedCategory);
        }

        $selectedSort = $request->input('sort', 'kategori');

        if ($selectedSort == 'nama_asc') {
            $barangQuery->orderBy('nama_barang', 'asc');
            $asetQuery->orderBy('nama_aset', 'asc');
        } elseif ($selectedSort == 'nama_desc') {
            $barangQuery->orderBy('nama_barang', 'desc');
            $asetQuery->orderBy('nama_aset', 'desc');
        } else {
            // Default: kategori
            $barangQuery->orderBy('kategori', 'asc')->orderBy('nama_barang', 'asc');
            $asetQuery->orderBy('kategori_aset', 'asc')->orderBy('nama_aset', 'asc');
        }

        $barangs = $barangQuery->get();
        $asets = $asetQuery->get();

        // Ambil semua kategori unik untuk filter
        $barangCats = Barang::where('status', 'aktif')->distinct()->pluck('kategori');
        $asetCats = Aset::whereIn('kondisi', ['baik', 'rusak_ringan'])->distinct()->pluck('kategori_aset');
        $categories = $barangCats->merge($asetCats)->unique()->sort()->values();

        return view('katalog.index', compact('barangs', 'asets', 'search', 'categories', 'selectedCategory', 'selectedSort'));
    }
}
