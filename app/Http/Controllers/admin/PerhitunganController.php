<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\Nasabah;
use App\Models\Subkriteria;
use Illuminate\Support\Facades\DB;
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

        // 1. Bobot Subkriteria
        foreach ($nasabahList as $nasabah) {
            $baris = [
                'id_nasabah' => $nasabah->id_nasabah,
                'nama_lengkap' => $nasabah->nama_lengkap,
            ];

            foreach ($kriteriaList as $kriteria) {
                $kolomNasabah = Str::snake(str_replace('jumlah', 'jml', strtolower($kriteria->nama_kriteria)));
                $nilaiNasabah = $nasabah->{$kolomNasabah} ?? null;

                $sub = SubKriteria::where('id_kriteria', $kriteria->id_kriteria)
                    ->where('nama_subkriteria', $nilaiNasabah)
                    ->first();

                $bobot = $sub?->bobot ?? 0;
                $baris[$kriteria->id_kriteria] = $bobot;

                if ($bobot == 0) {
                    $peringatan[] = "Nasabah '{$nasabah->nama_lengkap}' nilai '{$nilaiNasabah}' tidak cocok pada subkriteria '{$kriteria->nama_kriteria}'.";
                }
            }

            $dataAwal[] = $baris;
        }

        // 2. Hitung Max / Min
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

        // 3. Normalisasi
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
                    $row[$id] = $maxMin[$id]['max'] != 0 ? round($nilai / $maxMin[$id]['max'], 2) : 0;
                } else {
                    $row[$id] = $nilai != 0 ? round($maxMin[$id]['min'] / $nilai, 2) : 0;
                }
            }

            $normalisasi[] = $row;
        }

        // 4. Hitung Nilai Akhir
        $hasilAkhir = [];
        foreach ($normalisasi as $baris) {
            $total = 0;

            foreach ($kriteriaList as $kriteria) {
                $id = $kriteria->id_kriteria;
                $bobot = $kriteria->bobot;
                $nilai = $baris[$id];
                $total += $nilai * $bobot / 100;
            }

            $hasilAkhir[] = [
                'id_nasabah' => $baris['id_nasabah'],
                'nama_lengkap' => $baris['nama_lengkap'],
                'nilai_akhir' => round($total, 2),
            ];
        }

        $nilaiAkhirArray = array_column($hasilAkhir, 'nilai_akhir');

        // Cari nilai tertinggi dan terendah
        $nilaiTertinggi = max($nilaiAkhirArray);
        $nilaiTerendah = min($nilaiAkhirArray);

        // Hitung nilai tengah (bukan median, tapi midpoint dari rentang)
        $nilaiTengah = round(($nilaiTertinggi + $nilaiTerendah) / 2, 2);

        // 6. Tambahkan Status Layak / Tidak Layak
        foreach ($hasilAkhir as &$item) {
            $item['status'] = $item['nilai_akhir'] >= $nilaiTengah ? 'Layak' : 'Tidak Layak';
        }
        unset($item);

        // 7. Peringkat (Tangani nilai sama)
        usort($hasilAkhir, fn($a, $b) => $b['nilai_akhir'] <=> $a['nilai_akhir']);

        $peringkat = 1;
        $lastNilai = null;
        foreach ($hasilAkhir as $i => &$item) {
            if ($lastNilai !== null && $item['nilai_akhir'] === $lastNilai) {
                $item['peringkat'] = $peringkat; // sama
            } else {
                $peringkat = $i + 1;
                $item['peringkat'] = $peringkat;
            }

            $lastNilai = $item['nilai_akhir'];
        }
        unset($item);

        // Hapus semua data sebelumnya
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('perhitungan')->truncate(); // akan reset auto increment ke 1
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($hasilAkhir as $item) {
            DB::table('perhitungan')->insert([
                'id_nasabah'      => $item['id_nasabah'],
                'skor_akhir'      => $item['nilai_akhir'],
                'status_kelayakan'=> $item['status'],
                'tgl_hitung'      => now()->toDateString(),
                // 'status_konfimasi'=> 0,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }


        return Inertia::render('admin/Perhitungan/Index', [
            'dataAwal'     => $dataAwal,
            'normalisasi'  => $normalisasi,
            'hasilAkhir'   => $hasilAkhir,
            'kriteria'     => $kriteriaList,
            'peringatan'   => $peringatan,
            'nilai_max'    => $nilaiTertinggi,
            'nilai_min'    => $nilaiTerendah,
            'nilai_tengah' => $nilaiTengah,
        ]);
    }
}
