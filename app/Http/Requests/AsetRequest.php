<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsetRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nama_aset' => 'required|string|max:255',
            'kategori_aset' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat,tidak_layak',
            'tanggal_perolehan' => 'nullable|date',
            'nilai_perolehan' => 'nullable|numeric|min:0',
            'umur_manfaat' => 'nullable|integer|min:1',
            'keterangan' => 'nullable|string',
        ];
    }
}
