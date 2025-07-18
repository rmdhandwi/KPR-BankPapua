<?php

namespace App\Http\Controllers;

use App\Mail\KelayakanKPRFileMail;
use App\Mail\KelayakanKPRMail;
use App\Models\Nasabah;
use App\Models\Perhitungan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class LaporanController extends Controller
{
    public function index()
    {
        $data = Perhitungan::hasilAll();
        return Inertia::render('admin/laporan/Index', [
            'hasil' => $data
        ]);
    }

    public function kirimPersetujuan(Request $request)
    {
        $request->validate([
            'id_nasabah' => 'required|exists:nasabah,id_nasabah',
            'file' => 'required|file|mimes:pdf|max:2048',
        ], [
            'file.required' => 'File PDF wajib diunggah.',
            'file.mimes' => 'File harus berupa PDF.',
            'file.max' => 'Ukuran maksimal file 2MB.',
        ]);

        $nasabah = Nasabah::with('rumah')->findOrFail($request->id_nasabah);

        Mail::to($nasabah->email)->send(new KelayakanKPRFileMail(
            $nasabah->nama_lengkap,
            $nasabah->rumah->tipe ?? '-',
            $request->file('file')
        ));

        return back()->with('success', 'Email berhasil dikirim tanpa menyimpan file.');
    }

    public function print(Request $request)
    {
        $laporan = Perhitungan::hasilAll();
        return Inertia::render('admin/laporan/Print', [
            'laporan' => $laporan,
            'tanggal' => Carbon::now()->translatedFormat('l, d F Y'),
        ]);
    }
}
