<?php


namespace App\Services\Validation;


class ValidationRepeatedPassword extends AbstractValidation
{
    public function validate()
    {
        if (empty($this->param['repeatedPassword'])) {
            return $this->message = 'Введите пароль повторно';
        }

        if ($this->param['password'] !== $this->param['repeatedPassword']) {
            return $this->message = 'Пароли не совпадают';
        }

        return $this->isValid = true;
    }

}