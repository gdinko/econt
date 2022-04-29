<?php

namespace Gdinko\Econt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Gdinko\Econt\Models\CarrierEcontCountry
 *
 * @property int $id
 * @property int|null $econt_country_id
 * @property string|null $code2
 * @property string $code3
 * @property string $name
 * @property string|null $name_en
 * @property int|null $is_eu
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Gdinko\Econt\Models\CarrierEcontCity[] $cities
 * @property-read int|null $cities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Gdinko\Econt\Models\CarrierEcontOffice[] $offices
 * @property-read int|null $offices_count
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCountry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCountry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCountry query()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCountry whereCode2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCountry whereCode3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCountry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCountry whereEcontCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCountry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCountry whereIsEu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCountry whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCountry whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontCountry whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
