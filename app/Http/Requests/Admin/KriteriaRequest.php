<?php

namespace App\Http\Requests\Admin;

use App\Models\Kriteria;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KriteriaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('kriteria');
        if ($id instanceof Kriteria) {
            $id = $id->id_kriteria;
        }

        return [
            'nama_kriteria' => [
                'required',
                'string',
                Rule::unique('kriteria', 'nama_kriteria')->ignore($id, 'id_kriteria'),
            ],
            'bobot' => [
                'required',
                'numeric',
                'between:1,100',
                function ($attribute, $value, $fail) use ($id) {
                    $kriteria = \App\Models\Kriteria::find($id);
                    $bobotLama = $kriteria ? $kriteria->bobot : 0;

                    $totalBobot = \App\Models\Kriteria::sum('bobot') - $bobotLama + (float) $value;

                    if ($totalBobot > 100) {
                        $fail('Total bobot melebihi 100%. Sisa bobot tersedia: ' . (100 - (\App\Models\Kriteria::sum('bobot') - $bobotLama)) . '%.');
                    }
                },
            ],
            'jenis' => ['required', Rule::in(['Benefit', 'Cost'])],
        ];
    }


    public function messages(): array
    {
        return [
            'nama_kriteria.required' => 'Nama kriteria wajib diisi.',
            'nama_kriteria.string' => 'Nama kriteria harus berupa teks.',
            'nama_kriteria.unique' => 'Nama kriteria sudah digunakan.',

            'bobot.required' => 'Bobot wajib diisi.',
            'bobot.numeric' => 'Bobot harus berupa angka.',
            'bobot.between' => 'Bobot harus antara 1 sampai 100.',

            'jenis.required' => 'Jenis wajib diisi.',
            'jenis.string' => 'Jenis harus berupa Benefit atau Cost.',
        ];
    }
}
