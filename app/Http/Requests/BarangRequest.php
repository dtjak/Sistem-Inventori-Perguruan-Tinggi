<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $barangId = $this->route('barang')?->id;

        return [
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'stok_minimum' => 'required|integer|min:0',
            'stok_saat_ini' => 'nullable|integer|min:0',
            'lokasi_gudang' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'kategori.required' => 'Kategori wajib diisi.',
            'satuan.required' => 'Satuan wajib diisi.',
            'stok_minimum.required' => 'Stok minimum wajib diisi.',
            'stok_minimum.integer' => 'Stok minimum harus berupa angka.',
            'status.required' => 'Status wajib dipilih.',
        ];
    }
}
