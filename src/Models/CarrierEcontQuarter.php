<?php

namespace Gdinko\Econt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Gdinko\Econt\Models\CarrierEcontQuarter
 *
 * @property int $id
 * @property int $econt_quarter_id
 * @property int $econt_city_id
 * @property string $name
 * @property string|null $name_en
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Gdinko\Econt\Models\CarrierEcontCity|null $city
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontQuarter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontQuarter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontQuarter query()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontQuarter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontQuarter whereEcontCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontQuarter whereEcontQuarterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontQuarter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontQuarter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontQuarter whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontQuarter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CarrierEcontQuarter extends Model
{
    use HasFactory;

    protected $fillable = [
        'econt_quarter_id',
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
