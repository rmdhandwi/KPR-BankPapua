<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\NasabahDocumentRequest;
use App\Http\Requests\NasabahRequest;
use App\Models\Nasabah;
use App\Models\Rumah;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class NasabahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $columns = Schema::getColumnListing('nasabah');

        $data = Nasabah::with('rumah')->get()->map(function ($nasabah) {
            // Pastikan file dokumen dikonversi ke path relative storage jika tersedia
            $fileFields = ['KTP', 'NPWP', 'surat_nikah', 'spt_tahunan', 'kartu_keluarga'];

            foreach ($fileFields as $field) {
                if ($nasabah->{$field}) {
                    $nasabah->{$field} = 'storage/' . $nasabah->{$field}; // pastikan ini sesuai path yang digunakan saat upload
                } else {
                    $nasabah->{$field} = null;
                }
            }

            return $nasabah;
        });

        return Inertia::render('nasabah/Index', [
            'nasabah' => $data,
            'columns' => $columns,
        ]);
    }


    public function addColumn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'alpha_dash',
                'max:50',
            ],
        ], [
            'name.required' => 'Nama kolom wajib diisi.',
            'name.string' => 'Nama kolom harus berupa teks.',
            'name.alpha_dash' => 'Nama kolom hanya boleh mengandung huruf, angka, strip (-), dan underscore (_).',
            'name.max' => 'Nama kolom tidak boleh lebih dari :max karakter.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Validasi gagal. Periksa input Anda.');
        }

        $columnName = $request->input('name');

        if (Schema::hasColumn('nasabah', $columnName)) {
            return back()->with('error', "Kolom '{$columnName}' sudah ada dalam tabel nasabah.");
        }

        try {
            Schema::table('nasabah', function (Blueprint $table) use ($columnName) {
                $table->string($columnName)->nullable()->after('kelengkapan_berkas');
            });

            return back()->with('success', "Kolom '{$columnName}' berhasil ditambahkan.");
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menambahkan kolom: ' . $e->getMessage());
        }
    }

    public function removeColumn(Request $request)
    {
        $request->validate([
            'name' => 'required|string|alpha_dash|max:50',
        ]);

        $columnName = $request->name;

        // // Kolom-kolom tetap (tidak bisa dihapus)
        // $protected = [
        //     'id_nasabah',
        //     'id_rumah',
        //     'nama_lengkap',
        //     'no_ktp',
        //     'no_kk',
        //     'no_npwp',
        //     'tempat_lahir',
        //     'tgl_lahir',
        //     'pekerjaan',
        //     'nama_perusahan',
        //     'alamat_perusahaan',
        //     'email',
        //     'penghasilan',
        //     'no_tlp',
        //     'kewarganegaraan',
        //     'status_perkawinan',
        //     'jml_tanggungan',
        //     'riwayat_pinjol',
        //     'riwayat_kredit_macet',
        //     'NPWP',
        //     'KTP',
        //     'surat_nikah',
        //     'spt_tahunan',
        //     'kartu_keluarga',
        //     'kelengkapan_berkas',
        //     'created_at',
        //     'updated_at'
        // ];

        // if (in_array($columnName, $protected)) {
        //     return back()->with('error', 'Kolom tidak bisa dihapus');
        // }

        if (!Schema::hasColumn('nasabah', $columnName)) {
            return back()->with('error', 'Kolom tidak ditemukan');
        }

        Schema::table('nasabah', function (Blueprint $table) use ($columnName) {
            $table->dropColumn($columnName);
        });

        return back()->with('success', 'Kolom berhasil dihapus');
    }

    public function create()
    {
        $columns = Schema::getColumnListing('nasabah');
        $rumah = Rumah::select('id_rumah', 'nama', 'tipe')->get();

        return Inertia::render('nasabah/Form', [
            'columns' => $columns,
            'rumah' => $rumah
        ]);
    }

    public function store(NasabahRequest $request): RedirectResponse
    {
        try {
            $fileFields = ['KTP', 'NPWP', 'surat_nikah', 'spt_tahunan', 'kartu_keluarga'];
            $validated = $request->validated();

            // Buat data awal tanpa file
            $nasabahData = collect($validated)->except($fileFields)->toArray();

            // Tambahkan nilai default untuk kelengkapan_berkas
            $nasabahData['kelengkapan_berkas'] = 'Tidak Lengkap';

            // Simpan nasabah
            $nasabah = Nasabah::create($nasabahData);

            // Upload file
            $folder = 'nasabah/' . $nasabah->id_nasabah;
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = $field . '_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs($folder, $filename, 'public');
                    $nasabah->$field = $path;
                }
            }

            $nasabah->save();

            return redirect()->route('developer.nasabah.index')->with('success', 'Data nasabah berhasil ditambahkan.');
        } catch (\Throwable $e) {
            Log::error('Gagal store nasabah: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan data nasabah.');
        }
    }


    public function editInit(Request $request): RedirectResponse
    {
        $request->session()->put('edit_nasabah_id', $request->id);
        return redirect()->route('developer.nasabah.edit');
    }

    public function edit(Request $request)
    {
        $id = $request->session()->get('edit_nasabah_id');

        $data = Nasabah::find($id);
        if (!$data) {
            return redirect()->route('developer.nasabah.index')
                ->with('error', 'Data nasabah tidak ditemukan.');
        }

        $columns = Schema::getColumnListing('nasabah');
        $rumah = Rumah::select('id_rumah', 'nama', 'tipe')->get(); // jika diperlukan untuk select rumah

        return Inertia::render('nasabah/Form', [
            'data' => $data,
            'columns' => $columns,
            'rumah' => $rumah,
        ]);
    }


    public function update(NasabahRequest $request, Nasabah $nasabah): RedirectResponse
    {

        try {
            $validated = $request->validated();
            $fileFields = ['KTP', 'NPWP', 'surat_nikah', 'spt_tahunan', 'kartu_keluarga'];

            // Ambil data non-file
            $nonFileData = collect($validated)->except($fileFields);

            // Pertahankan kelengkapan_berkas lama jika tidak dikirim
            if (!$nonFileData->has('kelengkapan_berkas')) {
                $nonFileData->put('kelengkapan_berkas', $nasabah->kelengkapan_berkas);
            }

            // Persiapkan array data akhir untuk update
            $updateData = $nonFileData->toArray();

            $folder = 'nasabah/' . $nasabah->id_nasabah;

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    // Hapus file lama jika ada
                    if ($nasabah->$field && Storage::disk('public')->exists($nasabah->$field)) {
                        Storage::disk('public')->delete($nasabah->$field);
                    }

                    $file = $request->file($field);
                    $filename = $field . '_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs($folder, $filename, 'public');

                    // Masukkan ke data update
                    $updateData[$field] = $path;
                } elseif ($request->filled("{$field}_lama")) {
                    $nasabah->$field = $request->input("{$field}_lama");
                }
            }

            $nasabah->update($updateData);

            return redirect()->route('developer.nasabah.index')
                ->with('success', 'Data nasabah berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Gagal update nasabah: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }




    public function updateKelengkapan(Request $request, $id)
    {
        try {
            $request->validate([
                'kelengkapan_berkas' => [
                    'required',
                    Rule::in(['Lengkap', 'Tidak Lengkap'])
                ],
            ], [
                'kelengkapan_berkas.required' => 'Nama kolom wajib diisi.',
                'kelengkapan_berkas.in' => 'Status kelengkapan harus bernilai "Lengkap" atau "Tidak Lengkap".',
            ]);

            $nasabah = Nasabah::findOrFail($id);
            $nasabah->kelengkapan_berkas = $request->kelengkapan_berkas;
            $nasabah->save();

            return redirect()->route('admin.nasabah.index')->with('success', 'Status kelengkapan diperbarui.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.nasabah.index')
                ->with('error', 'Data nasabah tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.nasabah.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data.');
        };
    }

    public function destroy(Nasabah $nasabah): RedirectResponse
    {
        try {
            $fileFields = ['KTP', 'NPWP', 'surat_nikah', 'spt_tahunan', 'kartu_keluarga'];

            // Hapus semua file yang disimpan
            foreach ($fileFields as $field) {
                if ($nasabah->$field && Storage::disk('public')->exists($nasabah->$field)) {
                    Storage::disk('public')->delete($nasabah->$field);
                }
            }

            // Hapus direktori jika sudah kosong
            $folder = 'nasabah/' . $nasabah->id;
            if (Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->deleteDirectory($folder);
            }

            $nasabah->delete();

            return redirect()->route('developer.nasabah.index')->with('success', 'Data nasabah berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Gagal hapus nasabah: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus data nasabah.');
        }
    }
}
