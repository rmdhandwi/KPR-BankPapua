<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class NasabahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sesuaikan dengan kebutuhan autentikasi
    }

    public function rules(): array
    {
        $fixedRules = [
            'id_rumah' => 'required|exists:rumah,id_rumah',
            'nama_lengkap' => 'required|string|max:255',
            'no_ktp' => 'required|numeric|digits_between:14,20',
            'no_kk' => 'required|numeric|digits_between:14,20',
            'no_npwp' => 'nullable|string|max:30',
            'tempat_lahir' => 'required|string|max:100',
            'tgl_lahir' => 'required|date',
            'pekerjaan' => 'required|string|max:100',
            'nama_perusahan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'penghasilan' => 'required|string',
            'no_tlp' => 'required|string|max:20',
            'kewarganegaraan' => 'required|string|max:50',
            'status_perkawinan' => 'required|string',
            'jml_tanggungan' => 'required|string',
            'riwayat_pinjol' => 'required|string',
            'riwayat_kredit' => 'required|string',
            'kelengkapan_berkas' => ['nullable', Rule::in(['Lengkap', 'Tidak Lengkap'])],

            'KTP' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'NPWP' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat_nikah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'spt_tahunan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kartu_keluarga' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];

        $columns = Schema::getColumnListing('nasabah');
        $start = array_search('kelengkapan_berkas', $columns);

        $dynamicColumns = collect($columns)
            ->slice($start + 1)
            ->filter(fn($col) => !in_array($col, ['created_at', 'updated_at']))
            ->values();

        $dynamicRules = [];
        foreach ($dynamicColumns as $col) {
            $dynamicRules[$col] = 'nullable|string|max:255';
        }

        return array_merge($fixedRules, $dynamicRules);
    }

    public function messages(): array
    {
        return [
            '*.numeric' => 'Field ini harus berupa angka.',
            'id_rumah.required' => 'Data rumah wajib diisi.',
            'id_rumah.exists' => 'Data rumah tidak valid.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'no_ktp.required' => 'Nomor KTP wajib diisi.',
            'no_ktp.digits_between' => 'Nomor KTP harus antara 14 sampai 20 digit.',
            'no_kk.required' => 'Nomor KK wajib diisi.',
            'no_kk.digits_between' => 'Nomor KK harus antara 14 sampai 20 digit.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tgl_lahir.date' => 'Format tanggal lahir tidak valid.',
            'pekerjaan.required' => 'Pekerjaan wajib diisi.',
            'nama_perusahan.required' => 'Nama perusahaan wajib diisi.',
            'alamat_perusahaan.required' => 'Alamat perusahaan wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'penghasilan.required' => 'Penghasilan wajib dipilih.',
            'no_tlp.required' => 'Nomor telepon wajib diisi.',
            'kewarganegaraan.required' => 'Kewarganegaraan wajib diisi.',
            'status_perkawinan.required' => 'Status perkawinan wajib dipilih.',
            'jml_tanggungan.required' => 'Jumlah tanggungan wajib diisi.',
            'riwayat_pinjol.required' => 'Riwayat pinjol wajib dipilih.',
            'riwayat_kredit.required' => 'Riwayat kredit macet wajib dipilih.',

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
