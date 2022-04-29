<?php

namespace Gdinko\Econt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarrierEcontStreet extends Model
{
    use HasFactory;

    protected $fillable = [
        'econt_street_id',
        'econt_city_id',
        'name',
        'name_en',
    ];

    public function city()
    {
        return $this->belongsTo(
            CarrierEcontCity::class,
            'econt_city_id',
            'econt_city_id'
        );
    }
}
