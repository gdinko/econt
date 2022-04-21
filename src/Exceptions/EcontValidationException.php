<?php

namespace Gdinko\Econt\Exceptions;

use Exception;

class EcontValidationException extends Exception
{
    protected $errors = [];

    /**
     * __construct
     *
     * @param  string $message
     * @param  integer $code
     * @param  array $errors Validation Errors
     * @return void
     */
    public function __construct($message, $code = 0, $errors = [])
    {
        parent::__construct($message, $code);

        $this->errors = $errors;
    }
    
    /**
     * getErrors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
