<?php

namespace Gdinko\Econt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Gdinko\Econt\Models\CarrierEcontCity
 *
 * @property int $id
 * @property int $econt_city_id
 * @property string|null $country_code3
 * @property string|null $post_code
 * @property string $name
 * @property string|null $name_en
 * @property string|null $region_name
 * @property string|null $region_name_en
 * @property string|null $phone_code
 * @property string|null $location
 * @property int|null $express_city_deliveries
 * @property array|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Gdinko\Econt\Models\CarrierEcontOffice[] $offices
 * @property-read int|null $offices_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Gdinko\Econt\Models\CarrierEcontQuarter[] $quarters
 * @property-read int|null $quarters_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Gdinko\Econt\Models\CarrierEcontStreet[] $streets
 * @property-read int|null $streets_count
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCity query()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCity whereCountryCode3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCity whereEcontCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCity whereExpressCityDeliveries($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCity whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCity whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCity whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCity wherePhoneCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCity wherePostCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCity whereRegionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCity whereRegionNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCity whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
