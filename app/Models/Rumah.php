<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Rumah extends Model
{
    use HasFactory;

    protected $table = 'rumah'; // Nama tabel di database
    protected $primaryKey = 'id_rumah'; // Primary key khusus

    // public function __construct(array $attributes = [])
    // {
    //     parent::__construct($attributes);
    //     $this->fillable = Schema::getColumnListing($this->getTable());
    // }
    public $timestamps = true;

    // Field yang bisa diisi (mass assignment)
    protected $fillable = [
        'nama',
        'tipe',
        'luas_bangunan',
        'luas_tanah',
        'harga',
        'karakteristik',
        'created_at',
        'updated_at'
    ];

    public function rumahAll()
    {
        return Rumah::select([
            'id_rumah',
            'nama',
            'tipe',
            'luas_bangunan',
            'luas_tanah',
            'harga',
            'karakteristik'
        ])
            ->orderBy('tipe', 'asc')
            ->get();
    }


    public function Nasabah()
    {
        return $this->hasMany(Nasabah::class, 'id_rumah', 'id_rumah');
    }
}
