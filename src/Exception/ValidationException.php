<?php

namespace TransferWise\Exception;

class ValidationException extends \Exception
{

    protected $errors = [];

    /**
     * Initialize an instance of current class with data
     *
     * @param String  $message Message
     * @param Array   $errors  Validation errors  
     * @param Integer $code    Error code
     *
     * @return ValidationException
     */
    public static function instance(
        $message,
        $errors,
        $code
    ) {
        $instance = new static($message);
        $instance->code = $code;
        $instance->setErrors($errors);

        return $instance;
    }

    /**
     * Set Vaidation errors
     *
     * @param Array $errors Validation errors
     *
     * @return void
     */
    protected function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * Get Vaidation errors
     *
     * @return Array
     */
    public function getErrors()
    {
        return $this->errors;
    }

}