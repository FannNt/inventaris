<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'merk',
        'kondisi',
        'keterangan',
        'tahun_pengadaan',
        'masa_berlaku',
        "id_ruangan"
        ];
    public function ruangan()
    {

        return $this->belongsTo(Ruangan::class, 'id_ruangan','id');
    }
}
