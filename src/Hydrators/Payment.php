<?php

namespace Gdinko\Econt\Hydrators;

use Gdinko\Econt\Exceptions\EcontValidationException;
use Illuminate\Support\Facades\Validator;

class Payment
{
    protected $payment = [];

    /**
     * __construct
     *
     * @param  array $payment
     *
     * @return void
     */
    public function __construct(array $payment)
    {
        $this->payment = $payment;
    }

    /**
     * validationRules
     *
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'dateFrom' => 'date_format:Y-m-d|required',
            'dateTo' => 'date_format:Y-m-d|required',
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
            $this->payment,
            $this->validationRules()
        );

        if ($validator->fails()) {
            throw new EcontValidationException(
                __CLASS__,
                422,
                $validator->messages()->toArray()
            );
        }

        return $validator->validated();
    }
}
