<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NasabahDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'KTP' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'NPWP' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_nikah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'spt_tahunan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kartu_keluarga' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'KTP.file' => 'File KTP harus berupa file.',
            'KTP.mimes' => 'File KTP harus berupa JPG, JPEG, PNG, atau PDF.',
            'KTP.max' => 'Ukuran file KTP maksimal 2MB.',

            'NPWP.file' => 'File NPWP harus berupa file.',
            'NPWP.mimes' => 'File NPWP harus berupa JPG, JPEG, PNG, atau PDF.',
            'NPWP.max' => 'Ukuran file NPWP maksimal 2MB.',

            'surat_nikah.file' => 'File surat nikah harus berupa file.',
            'surat_nikah.mimes' => 'File surat nikah harus berupa JPG, JPEG, PNG, atau PDF.',
            'surat_nikah.max' => 'Ukuran file surat nikah maksimal 2MB.',

            'spt_tahunan.file' => 'File SPT Tahunan harus berupa file.',
            'spt_tahunan.mimes' => 'File SPT Tahunan harus berupa JPG, JPEG, PNG, atau PDF.',
            'spt_tahunan.max' => 'Ukuran file SPT Tahunan maksimal 2MB.',

            'kartu_keluarga.file' => 'File Kartu Keluarga harus berupa file.',
            'kartu_keluarga.mimes' => 'File Kartu Keluarga harus berupa JPG, JPEG, PNG, atau PDF.',
            'kartu_keluarga.max' => 'Ukuran file Kartu Keluarga maksimal 2MB.',
        ];
    }
}
