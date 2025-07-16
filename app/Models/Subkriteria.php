<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subkriteria extends Model
{
    // Jika menggunakan UUID atau custom id yang bukan auto-increment, atur key-nya
    protected $primaryKey = 'id_subkriteria';

    // Jika primary key bukan auto-increment (misal UUID), ubah ini:
    // public $incrementing = false;

    // Jika id bukan integer (misal UUID string)
    // protected $keyType = 'string';

    // Optional: Table name (jika bukan jamak dari nama model)
    protected $table = 'sub_kriteria';

    // Jika tidak menggunakan timestamps
    public $timestamps = true;

    protected $fillable = [
        'id_subkriteria',
        'id_kriteria',
        'nama_subkriteria',
        'bobot',
        'created_at',
        'updated_at'
    ];


    public static function subkriteriaAll()
    {
        return Subkriteria::with('kriteria')
            ->orderBy(
                Kriteria::select('nama_kriteria')
                    ->whereColumn('kriteria.id_kriteria', 'sub_kriteria.id_kriteria')
            )
            ->get();
    }
    
    // Opsional jika ada relasi
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria', 'id_kriteria');
    }
}
