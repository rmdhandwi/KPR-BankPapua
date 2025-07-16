<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Nasabah extends Model
{
    // Jika menggunakan UUID atau custom id yang bukan auto-increment, atur key-nya
    protected $primaryKey = 'id_nasabah';

    // Optional: Table name (jika bukan jamak dari nama model)
    protected $table = 'nasabah';
    public $timestamps = true;

    // Jika primary key bukan auto-increment (misal UUID), ubah ini:
    // public $incrementing = false;

    // Jika id bukan integer (misal UUID string)
    // protected $keyType = 'string';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->fillable = Schema::getColumnListing($this->getTable());
    }

    public function getNasabahWithRumah()
    {
        return Nasabah::with(['rumah:id_rumah,nama,tipe'])
            ->get();
            // ->map(function ($item) {
            //     $nasabahData = $item->toArray();
            //     $nasabahData['nama_rumah'] = $item->rumah->nama ?? null;
            //     $nasabahData['tipe_rumah'] = $item->rumah->tipe ?? null;
            //     unset($nasabahData['rumah']);
            //     return $nasabahData;
            // });
    }

    public function rumah()
    {
        return $this->belongsTo(Rumah::class, 'id_rumah', 'id_rumah');
    }

}
