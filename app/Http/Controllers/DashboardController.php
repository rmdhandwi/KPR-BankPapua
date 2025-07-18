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
        $user = Auth::user();

        // Data yang selalu tersedia
        $nasabahCount = Nasabah::count();
        $rumahCount = Rumah::count();

        // Data tambahan untuk admin
        $kriteriaCount = null;
        $subKriteriaCount = null;
        $nasabahLengkap = null;
        $nasabahTidakLengkap = null;

        if ($user->role === 1) {
            $kriteriaCount = Kriteria::count();
            $subKriteriaCount = Subkriteria::count();
            $nasabahLengkap = Nasabah::where('kelengkapan_berkas', 'Lengkap')->count();
            $nasabahTidakLengkap = Nasabah::where('kelengkapan_berkas', 'Tidak Lengkap')->count();
        }

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
