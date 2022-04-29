<?php

namespace Gdinko\Econt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarrierEcontCity extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'econt_city_id',
        'country_code3',
        'post_code',
        'name',
        'name_en',
        'region_name',
        'region_name_en',
        'phone_code',
        'location',
        'express_city_deliveries',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function streets()
    {
        return $this->hasMany(
            CarrierEcontStreet::class,
            'econt_city_id',
            'econt_city_id'
        );
    }

    public function quarters()
    {
        return $this->hasMany(
            CarrierEcontQuarter::class,
            'econt_city_id',
            'econt_city_id'
        );
    }

    public function offices()
    {
        return $this->hasMany(
            CarrierEcontOffice::class,
            'econt_city_id',
            'econt_city_id'
        );
    }
}
