<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    // Jika menggunakan UUID atau custom id yang bukan auto-increment, atur key-nya
    protected $primaryKey = 'id_kriteria';

    // Jika primary key bukan auto-increment (misal UUID), ubah ini:
    // public $incrementing = false;

    // Jika id bukan integer (misal UUID string)
    // protected $keyType = 'string';

    protected $fillable = [
        'id_kriteria',
        'nama_kriteria',
        'bobot',
        'jenis',
        'created_at',
        'updated_at',
    ];

    // Jika tidak menggunakan timestamps
    public $timestamps = true;

    // Optional: Table name (jika bukan jamak dari nama model)
    protected $table = 'kriteria';

    public static function allKriteria()
    {
        return Kriteria::query()
            ->select(['id_kriteria', 'nama_kriteria', 'bobot', 'jenis'])
            // ->orderBy('nama_kriteria')
            ->get();
    }

    public function subkriteria()
    {
        return $this->hasMany(Subkriteria::class, 'id_kriteria', 'id_kriteria');
    }
}
