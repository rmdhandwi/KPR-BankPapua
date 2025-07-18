<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KirimPersetujuanRequest extends FormRequest
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
        return [
            'id_nasabah' => 'required|exists:nasabah,id',
            'file' => 'required|file|mimes:pdf|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'id_nasabah.required' => 'ID nasabah wajib diisi.',
            'id_nasabah.exists' => 'Nasabah yang dipilih tidak ditemukan.',
            'file.required' => 'File PDF persetujuan wajib diunggah.',
            'file.file' => 'File yang dikirim tidak valid.',
            'file.mimes' => 'File harus berupa PDF.',
            'file.max' => 'Ukuran file maksimal 2MB.',
        ];
    }
}
