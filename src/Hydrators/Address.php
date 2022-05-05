<?php

namespace Gdinko\Econt\Hydrators;

use Gdinko\Econt\Exceptions\EcontValidationException;
use Illuminate\Support\Facades\Validator;

class Address
{
    protected $address = [];

    /**
     * __construct
     *
     * @param  array $address
     * @return void
     */
    public function __construct(array $address)
    {
        $this->address = $address;
    }

    /**
     * validationRules
     *
     * @return array
     */
    public function validationRules(): array
    {
        return [
            'id' => 'integer|sometimes',
            'city' => 'array|sometimes',
            'city.name' => 'string|sometimes|required_without:fullAddress',
            'fullAddress' => 'string|sometimes',
            'quarter' => 'string|sometimes|required_without_all:fullAddress,street',
            'street' => 'string|sometimes|required_without_all:fullAddress,quarter|required_with:num',
            'num' => 'string|sometimes|required_without_all:fullAddress,quarter|required_with:street',
            'other' => 'string|sometimes',
            'location' => 'array|sometimes',
            'location.latitude' => 'integer|sometimes|required_with:location.longitude',
            'location.longitude' => 'integer|sometimes|required_with:location.latitude',
            'location.confidence' => 'integer|sometimes',
            'zip' => 'string|sometimes',
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
            $this->address,
            $this->validationRules()
        );

        if ($validator->fails()) {
            throw new EcontValidationException(
                Address::class,
                422,
                $validator->messages()->toArray()
            );
        }

        return [
            'address' => $validator->validated(),
        ];
    }
}
