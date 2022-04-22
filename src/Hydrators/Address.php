<?php

namespace Gdinko\Econt\Hydrators;

use Gdinko\Econt\Exceptions\EcontValidationException;
use Illuminate\Support\Facades\Validator;

class Address
{
    protected $address = [];

    public function __construct(array $address)
    {
        $this->address = $address;
    }

    public function validated()
    {
        $validator = Validator::make($this->address, [
            'id' => 'integer|nullable',
            'city' => 'array|nullable',
            'city.name' => 'string|nullable|required_without:fullAddress',
            'fullAddress' => 'string|nullable',
            'quarter' => 'string|nullable|required_without_all:fullAddress,street',
            'street' => 'string|nullable|required_without_all:fullAddress,quarter|required_with:num',
            'num' => 'string|nullable|required_without_all:fullAddress,quarter|required_with:street',
            'other' => 'string|nullable',
            'location' => 'array|nullable',
            'location.latitude' => 'integer|sometimes|required_with:location.longitude',
            'location.longitude' => 'integer|sometimes|required_with:location.latitude',
            'location.confidence' => 'integer|sometimes',
            'zip' => 'string|nullable',
        ]);

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
