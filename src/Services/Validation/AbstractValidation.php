<?php


namespace App\Services\Validation;


abstract class AbstractValidation implements ValidateInterface
{
    protected $param;
    protected $message;
    protected $isValid;

    public function __construct($param)
    {
        $this->param = $param;
        $this->isValid = false;
        $this->validate();
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function isValid()
    {
        return $this->isValid;
    }

}