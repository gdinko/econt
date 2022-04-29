<?php

namespace Gdinko\Econt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarrierEcontCountry extends Model
{
    use HasFactory;

    protected $fillable = [
        'econt_country_id',
        'code2',
        'code3',
        'name',
        'name_en',
        'is_eu',
    ];

    public function cities()
    {
        return $this->hasMany(
            CarrierEcontCity::class,
            'country_code3',
            'code3'
        );
    }

    public function offices()
    {
        return $this->hasMany(
            CarrierEcontOffice::class,
            'country_code3',
            'code3'
        );
    }
}
