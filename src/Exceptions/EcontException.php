<?php

namespace Gdinko\Econt\Exceptions;

use Exception;

class EcontException extends Exception
{
    protected $type = null;

    protected $fields = [];

    protected $errors = [];

    /**
     * __construct
     *
     * @param  string $message
     * @param  integer $code
     * @param  string $type Econt Exception Type
     * @param  array $fields Econt Fields
     * @param  array $errors Econt Errors
     * @return void
     */
    public function __construct($message, $code = 0, $type = null, $fields = [], $errors = [])
    {
        parent::__construct($message, $code);

        $this->type = $type;

        $this->fields = $fields;

        $this->errors = $errors;
    }

    /**
     * getType
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * getFields
     *
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
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
