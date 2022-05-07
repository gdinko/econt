<?php

namespace Gdinko\Econt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Gdinko\Econt\Models\CarrierEcontApiStatus
 *
 * @property int $id
 * @property int $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontApiStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontApiStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontApiStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontApiStatus whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontApiStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontApiStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontApiStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CarrierEcontApiStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
    ];
}
