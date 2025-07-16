<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\Nasabah;
use App\Models\Subkriteria;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PerhitunganController extends Controller
{
    public function index()
    {
        $nasabahList = Nasabah::all();
        $kriteriaList = Kriteria::get();

        $dataAwal = [];
        $peringatan = [];

        foreach ($nasabahList as $nasabah) {
            $baris = [
                'id_nasabah' => $nasabah->id_nasabah,
                'nama_lengkap' => $nasabah->nama_lengkap,
            ];

            foreach ($kriteriaList as $kriteria) {
                // Konversi nama_kriteria ke nama kolom di nasabah
                $kolomNasabah = Str::snake(str_replace('jumlah', 'jml', strtolower($kriteria->nama_kriteria)));

                // Ambil nilai dari kolom nasabah tersebut
                $nilaiNasabah = $nasabah->{$kolomNasabah} ?? null;

                // Cari subkriteria berdasarkan nilai nasabah
                $sub = SubKriteria::where('id_kriteria', $kriteria->id_kriteria)
                    ->where('nama_subkriteria', $nilaiNasabah)
                    ->first();

                $bobot = $sub?->bobot ?? 0;
                $baris[$kriteria->id_kriteria] = $bobot;

                // Peringatan jika nilai tidak dikenali
                if ($bobot == 0) {
                    $peringatan[] = "Nasabah '{$nasabah->nama_lengkap}' nilai '{$nilaiNasabah}' tidak cocok pada subkriteria '{$kriteria->nama_kriteria}'.";
                }
            }

            $dataAwal[] = $baris;
        }

        // Hitung max/min untuk normalisasi
        $maxMin = [];
        foreach ($kriteriaList as $kriteria) {
            $kolom = $kriteria->id_kriteria;
            $nilaiKolom = array_column($dataAwal, $kolom);

            $maxMin[$kolom] = [
                'max' => max($nilaiKolom),
                'min' => min($nilaiKolom),
                'jenis' => $kriteria->jenis,
            ];
        }

        // Normalisasi
        $normalisasi = [];
        foreach ($dataAwal as $baris) {
            $row = [
                'id_nasabah' => $baris['id_nasabah'],
                'nama_lengkap' => $baris['nama_lengkap'],
            ];

            foreach ($kriteriaList as $kriteria) {
                $id = $kriteria->id_kriteria;
                $nilai = $baris[$id];

                if ($maxMin[$id]['jenis'] === 'Benefit') {
                    $row[$id] = $maxMin[$id]['max'] != 0 ?  round($nilai / $maxMin[$id]['max'], 2) : 0;
                } else {
                    $row[$id] = $nilai != 0 ?  round($maxMin[$id]['min'] / $nilai, 2)  : 0;
                }
            }

            $normalisasi[] = $row;
        }

        // Hitung nilai akhir
        $hasilAkhir = [];
        foreach ($normalisasi as $baris) {
            $total = 0;

            foreach ($kriteriaList as $kriteria) {
                $id = $kriteria->id_kriteria;
                $bobot = $kriteria->bobot;
                $nilai = $baris[$id];

                // dd("Normalisasi: $nilai, Bobot: $bobot");
                $total += $nilai * $bobot / 100;
            }


            $hasilAkhir[] = [
                'id_nasabah' => $baris['id_nasabah'],
                'nama_lengkap' => $baris['nama_lengkap'],
                'nilai_akhir' => round($total, 2),
            ];
        }

        // Urutkan dari tertinggi
        usort($hasilAkhir, fn($a, $b) => $a['nilai_akhir'] <=> $b['nilai_akhir']);

        // Tambahkan peringkat
        foreach ($hasilAkhir as $i => &$item) {
            $item['peringkat'] = $i + 1;
        }


        return Inertia::render('admin/Perhitungan/Index', [
            'dataAwal'     => $dataAwal,
            'normalisasi'  => $normalisasi,
            'hasilAkhir'   => $hasilAkhir,
            'kriteria'     => $kriteriaList,
            'peringatan'   => $peringatan,
        ]);
    }
}
