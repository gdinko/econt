<?php

namespace Gdinko\Econt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Gdinko\Econt\Models\CarrierEcontTracking
 *
 * @property int $id
 * @property int $parcel_id
 * @property string $carrier_signature
 * @property string $carrier_account
 * @property array|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontTracking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontTracking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontTracking query()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontTracking whereCarrierAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontTracking whereCarrierSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontTracking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontTracking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontTracking whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontTracking whereParcelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontTracking whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CarrierEcontTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'parcel_id',
        'carrier_signature',
        'carrier_account',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
