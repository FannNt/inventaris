<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
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
        if (!$this->masa_berlaku) {
            return false;
        }
        return Carbon::parse($this->masa_berlaku)->lt(now());
    }

    public function isExpiringSoon()
    {
        if (!$this->masa_berlaku) {
            return false;
        }
        $expiryDate = Carbon::parse($this->masa_berlaku);
        return !$this->isExpired() && $expiryDate->lt(now()->addMonths(3));
    }

    public function isValid()
    {
        if (!$this->masa_berlaku) {
            return true;
        }
        return Carbon::parse($this->masa_berlaku)->gt(now()->addMonths(3));
    }

    public function getExpirationStatus()
    {
        if ($this->isExpired()) {
            return 'expired';
        } elseif ($this->isExpiringSoon()) {
            return 'expiring_soon';
        } else {
            return 'valid';
        }
    }

    public function getExpirationCardClass()
    {
        if (!$this->masa_berlaku) {
            return 'border-gray-200';
        }

        return match($this->getExpirationStatus()) {
            'expired' => 'border-red-200 bg-red-50',
            'expiring_soon' => 'border-yellow-200 bg-yellow-50',
            'valid' => 'border-green-200 bg-green-50',
        };
    }

    public function getExpirationTextClass()
    {
        if (!$this->masa_berlaku) {
            return 'text-gray-900';
        }

        return match($this->getExpirationStatus()) {
            'expired' => 'text-red-600 font-medium',
            'expiring_soon' => 'text-yellow-600 font-medium',
            'valid' => 'text-green-600 font-medium',
        };
    }
    public function ruangan()
    {

        return $this->belongsTo(Ruangan::class, 'id_ruangan','id');
    }
}
