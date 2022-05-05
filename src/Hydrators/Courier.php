<?php

namespace Gdinko\Econt\Hydrators;

use Gdinko\Econt\Exceptions\EcontValidationException;
use Illuminate\Support\Facades\Validator;

class Courier
{
    protected $courier = [];

    /**
     * __construct
     *
     * @param  array $courier
     * @return void
     */
    public function __construct(array $courier)
    {
        $this->courier = $courier;
    }

    /**
     * validationRules
     *
     * @return array
     */
    public function validationRules(): array
    {
        return [
            'requestTimeFrom' => 'date_format:Y-m-d H:i:s|after:now|required',
            'requestTimeTo' => 'date_format:Y-m-d H:i:s|after:requestTimeFrom|required',
            'shipmentType' => 'string|required',
            'shipmentPackCount' => 'numeric|sometimes',
            'shipmentWeight' => 'numeric|sometimes',
            'senderClient' => 'array|required',
            'senderAgent' => 'array|sometimes',
            'senderAddress' => 'array|sometimes',
        ];
    }

    /**
     * validated
     *
     * @return array
     *
     * @throws Exception
     */
    public function validated(): array
    {
        $validator = Validator::make(
            $this->courier,
            $this->validationRules()
        );

        if ($validator->fails()) {
            throw new EcontValidationException(
                Address::class,
                422,
                $validator->messages()->toArray()
            );
        }

        return $validator->validated();
    }
}
