<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'uuid',
        'lab_configure',
        'no_seri',
        'type',
        'name',
        'merk',
        'kondisi',
        'keterangan',
        'tahun_pengadaan',
        'masa_berlaku',
        "id_ruangan"
        ];

    protected $dates = [
        'masa_berlaku'
    ];
    public function isExpired()
    {
        $warningDays = 90;
        $today = now();
        $masa_berlaku = Carbon::parse($this->masa_berlaku);
        return $masa_berlaku->lte($today) ||
            $today->diffInDays($masa_berlaku) <= $warningDays;
    }
    public function ruangan()
    {

        return $this->belongsTo(Ruangan::class, 'id_ruangan','id');
    }
}
