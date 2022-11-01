<?php

namespace Gdinko\Econt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Gdinko\Econt\Models\CarrierEcontOffice
 *
 * @property int $id
 * @property int $econt_office_id
 * @property string $code
 * @property string $country_code3
 * @property string $econt_city_id
 * @property int|null $is_mps
 * @property int|null $is_aps
 * @property string $name
 * @property string|null $name_en
 * @property array|null $phones
 * @property array|null $emails
 * @property array|null $address
 * @property string|null $info
 * @property string|null $currency
 * @property string|null $language
 * @property string|null $normal_business_hours_from
 * @property string|null $normal_business_hours_to
 * @property string|null $half_day_business_hours_from
 * @property string|null $half_day_business_hours_to
 * @property array|null $shipment_types
 * @property string|null $partner_code
 * @property string|null $hub_code
 * @property string|null $hub_name
 * @property string|null $hub_name_en
 * @property array|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Gdinko\Econt\Models\CarrierEcontCity|null $city
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice query()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereCountryCode3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereEcontCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereEcontOfficeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereEmails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereHalfDayBusinessHoursFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereHalfDayBusinessHoursTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereHubCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereHubName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereHubNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereIsAps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereIsMps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereNormalBusinessHoursFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereNormalBusinessHoursTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice wherePartnerCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice wherePhones($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereShipmentTypes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $city_uuid
 * @property int|null $is_robot
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereCityUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontOffice whereIsRobot($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Gdinko\Econt\Models\CarrierCityMap[] $cityMap
 * @property-read int|null $city_map_count
 */
class CarrierEcontOffice extends Model
{
    use HasFactory;

    protected $fillable = [
        'econt_office_id',
        'code',
        'country_code3',
        'econt_city_id',
        'city_uuid',
        'is_mps',
        'is_aps',
        'is_robot',
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

    public function cityMap()
    {
        return $this->hasMany(
            CarrierCityMap::class,
            'uuid',
            'city_uuid'
        );
    }
}
