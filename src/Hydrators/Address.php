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
    protected function validationRules(): array
    {
        return [
            'id' => 'integer|nullable',
            'city' => 'array|nullable',
            'city.name' => 'string|nullable|required_without:fullAddress',
            'fullAddress' => 'string|nullable',
            'quarter' => 'string|nullable|required_without_all:fullAddress,street',
            'street' => 'string|nullable|required_without_all:fullAddress,quarter|required_with:num',
            'num' => 'string|nullable|required_without_all:fullAddress,quarter|required_with:street',
            'other' => 'string|nullable',
            'location' => 'array|nullable',
            'location.latitude' => 'integer|nullable|required_with:location.longitude',
            'location.longitude' => 'integer|nullable|required_with:location.latitude',
            'location.confidence' => 'integer|nullable',
            'zip' => 'string|nullable',
        ];
    }

    /**
     * validated
     *
     * @return array
     *
     * @throws EcontValidationException
     */
    public function validated(): array
    {
        $validator = Validator::make(
            $this->address,
            $this->validationRules()
        );

        if ($validator->fails()) {
            throw new EcontValidationException(
                __CLASS__,
                422,
                $validator->messages()->toArray()
            );
        }

        return [
            'address' => $validator->validated(),
        ];
    }
}
