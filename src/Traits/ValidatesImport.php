<?php

namespace Gdinko\Econt\Traits;

use Gdinko\Econt\Exceptions\EcontImportValidationException;
use Illuminate\Support\Facades\Validator;

trait ValidatesImport
{
    /**
     * validated
     *
     * @param  array $data
     * @return array
     */
    protected function validated(array $data): array
    {
        $validator = Validator::make(
            $data,
            $this->validationRules()
        );

        if ($validator->fails()) {
            throw new EcontImportValidationException(
                __CLASS__,
                422,
                $validator->messages()->toArray()
            );
        }

        return $validator->validated();
    }
}
