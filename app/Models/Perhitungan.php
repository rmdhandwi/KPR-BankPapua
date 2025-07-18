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
        'status_konfirmasi',
        'created_at',
        'updated_at'
    ];

    public function rumahAll()
    {
        return Perhitungan::select([
            'id_perhitungan',
            'id_nasabah',
            'skor_akhir',
            'status_kelayakan',
            'tgl_hitung',
            'status_konfirmasi',
        ])
            ->orderByRaw(
                "
                    CASE 
                        WHEN status_kelayakan = 'Layak' THEN 1
                        WHEN status_kelayakan = 'Tidak Layak' THEN 2
                        ELSE 3
                    END
                "
            )
            ->get();
    }


    public function Nasabah()
    {
        return $this->hasMany(Nasabah::class, 'id_rumah', 'id_rumah');
    }
}
