<?php

namespace App\Http\Requests\developer;

use Illuminate\Foundation\Http\FormRequest;

class RumahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'tipe' => 'required|string|max:50',
            'luas_bangunan' => 'required|numeric|min:0',
            'luas_tanah' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
            'karakteristik' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama rumah wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama maksimal 255 karakter.',

            'tipe.required' => 'Tipe rumah wajib diisi.',
            'tipe.string' => 'Tipe harus berupa teks.',
            'tipe.max' => 'Tipe maksimal 50 karakter.',

            'luas_bangunan.required' => 'Luas bangunan wajib diisi.',
            'luas_bangunan.numeric' => 'Luas bangunan harus berupa angka.',
            'luas_bangunan.min' => 'Luas bangunan tidak boleh negatif.',

            'luas_tanah.required' => 'Luas tanah wajib diisi.',
            'luas_tanah.numeric' => 'Luas tanah harus berupa angka.',
            'luas_tanah.min' => 'Luas tanah tidak boleh negatif.',

            'harga.required' => 'Harga rumah wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh negatif.',

            'karakteristik.string' => 'Karakteristik harus berupa teks.',
        ];
    }
}
