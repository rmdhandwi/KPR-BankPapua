<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\Nasabah;
use App\Models\Rumah;
use App\Models\Subkriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        // Data yang selalu tersedia untuk semua role
        $nasabahCount = Nasabah::count(); // Total nasabah
        $rumahCount = Rumah::count();     // Total rumah

        // Inisialisasi variabel tambahan untuk admin (role 1)
        $kriteriaCount = null;
        $subKriteriaCount = null;
        $nasabahLengkap = null;
        $nasabahTidakLengkap = null;

        // Jika user adalah admin
        if ($user->role === 1) {
            // Hitung total kriteria dan subkriteria
            $kriteriaCount = Kriteria::count();
            $subKriteriaCount = Subkriteria::count();

            // Hitung nasabah berdasarkan kelengkapan berkas
            $nasabahLengkap = Nasabah::where('kelengkapan_berkas', 'Lengkap')->count();
            $nasabahTidakLengkap = Nasabah::where('kelengkapan_berkas', 'Tidak Lengkap')->count();
        }

        // Render halaman dashboard dengan data yang sudah dikumpulkan
        return Inertia::render('Dashboard', [
            'nasabahCount' => $nasabahCount,
            'rumahCount' => $rumahCount,
            'kriteriaCount' => $kriteriaCount,
            'subKriteriaCount' => $subKriteriaCount,
            'nasabahLengkap' => $nasabahLengkap,
            'nasabahTidakLengkap' => $nasabahTidakLengkap,
        ]);
    }
}
