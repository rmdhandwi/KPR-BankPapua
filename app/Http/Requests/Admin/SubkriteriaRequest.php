<?php

namespace App\Http\Requests\admin;

use App\Models\Subkriteria;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubkriteriaRequest extends FormRequest
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
        $id = $this->route('subkriteria');
        return [
            'id_kriteria' => ['required', 'exists:kriteria,id_kriteria'],
            'nama_subkriteria' => [
                'required',
                'string',
                Rule::unique('sub_kriteria', 'nama_subkriteria')->ignore($id, 'id_subkriteria'),
            ],
            'bobot' => [
                'required',
                'numeric',
                'between:1,5',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_kriteria.required' => 'Kriteria wajib dipilih.',
            'id_kriteria.exists' => 'Kriteria tidak valid.',
            'nama_subkriteria.required' => 'Nama kriteria wajib diisi.',
            'nama_subkriteria.unique' => 'Nama kriteria sudah digunakan.',
            'bobot.required' => 'Bobot wajib diisi.',
            'bobot.numeric' => 'Bobot harus berupa angka.',
            'bobot.between' => 'Bobot harus antara 1 sampai 5.',
        ];
    }
}
