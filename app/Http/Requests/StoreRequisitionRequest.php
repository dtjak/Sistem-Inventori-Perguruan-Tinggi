<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequisitionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'tanggal' => 'required|date',
            'unit_peminjam' => 'required|string|max:255',
            'catatan' => 'nullable|string',
            'details' => 'required|array|min:1',
            'details.*.barang_id' => 'required_without:details.*.aset_id|nullable|exists:barangs,id',
            'details.*.aset_id' => 'required_without:details.*.barang_id|nullable|exists:asets,id',
            'details.*.qty' => 'required|integer|min:1',
            'details.*.keterangan' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'details.required' => 'Minimal tambahkan satu item.',
            'details.*.barang_id.required_without' => 'Pilih barang atau aset.',
            'details.*.aset_id.required_without' => 'Pilih barang atau aset.',
            'details.*.qty.required' => 'Qty wajib diisi.',
            'details.*.qty.min' => 'Qty minimal 1.',
        ];
    }
}
