<?php

namespace Gdinko\Econt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Gdinko\Econt\Models\CarrierEcontStreet
 *
 * @property int $id
 * @property int $econt_street_id
 * @property int $econt_city_id
 * @property string $name
 * @property string|null $name_en
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Gdinko\Econt\Models\CarrierEcontCity|null $city
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontStreet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontStreet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontStreet query()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontStreet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontStreet whereEcontCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontStreet whereEcontStreetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontStreet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontStreet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontStreet whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontStreet whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
