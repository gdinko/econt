<?php

namespace Gdinko\Econt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Gdinko\Econt\Models\CarrierEcontPayment
 *
 * @property int $id
 * @property string $num
 * @property string $type
 * @property string $pay_type
 * @property string $pay_date
 * @property string $amount
 * @property string $currency
 * @property string $created_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontPayment whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontPayment whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontPayment whereNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontPayment wherePayDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontPayment wherePayType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontPayment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarrierEcontPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CarrierEcontPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'num',
        'type',
        'pay_type',
        'pay_date',
        'amount',
        'currency',
        'created_time',
    ];
}
