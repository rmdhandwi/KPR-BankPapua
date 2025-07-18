<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perhitungan extends Model
{
    use HasFactory;

    protected $table = 'perhitungan'; // Nama tabel di database
    protected $primaryKey = 'id_perhitungan'; // Primary key khusus

    // public function __construct(array $attributes = [])
    // {
    //     parent::__construct($attributes);
    //     $this->fillable = Schema::getColumnListing($this->getTable());
    // }
    public $timestamps = true;

    // Field yang bisa diisi (mass assignment)
    protected $fillable = [
        'id_perhitungan',
        'id_nasabah',
        'skor_akhir',
        'status_kelayakan',
        'tgl_hitung',
        'created_at',
        'updated_at'
    ];

    public static function hasilAll()
    {
        return Perhitungan::join('nasabah', 'nasabah.id_nasabah', '=', 'perhitungan.id_nasabah')
            ->join('rumah', 'rumah.id_rumah', '=', 'nasabah.id_rumah') // join rumah
            ->select([
                'perhitungan.id_perhitungan',
                'perhitungan.id_nasabah',
                'nasabah.nama_lengkap',
                'nasabah.email',
                'rumah.nama',
                'rumah.tipe',
                'perhitungan.skor_akhir',
                'perhitungan.status_kelayakan',
                'perhitungan.tgl_hitung',
            ])
            ->orderByRaw("
        CASE 
            WHEN perhitungan.status_kelayakan = 'Layak' THEN 1
            WHEN perhitungan.status_kelayakan = 'Tidak Layak' THEN 2
            ELSE 3
        END
    ")
            ->get();
    }

    // public function nasabah()
    // {
    //     return $this->belongsTo(Nasabah::class, 'id_nasabah', 'id_nasabah');
    // }
    
}
