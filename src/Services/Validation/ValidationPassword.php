<?php


namespace App\Services\Validation;


class ValidationPassword extends AbstractValidation
{
    public function validate()
    {
        if (empty($this->param)) {
            return $this->message = 'Пароль не может быть пустым';
        }
        return $this->isValid = true;
    }


}