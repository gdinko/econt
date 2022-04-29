<?php

namespace Gdinko\Econt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarrierEcontOffice extends Model
{
    use HasFactory;

    protected $fillable = [
        'econt_office_id',
        'code',
        'country_code3',
        'econt_city_id',
        'is_mps',
        'is_aps',
        'name',
        'name_en',
        'phones',
        'emails',
        'address',
        'info',
        'currency',
        'language',
        'normal_business_hours_from',
        'normal_business_hours_to',
        'half_day_business_hours_from',
        'half_day_business_hours_to',
        'shipment_types',
        'partner_code',
        'hub_code',
        'hub_name',
        'hub_name_en',
        'meta',
    ];

    protected $casts = [
        'phones' => 'array',
        'emails' => 'array',
        'address' => 'array',
        'shipment_types' => 'array',
        'meta' => 'array',
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
