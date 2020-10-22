<?php


namespace App\Services\Validation;


class ValidationPassword extends AbstractValidation
{
    public function validate()
    {
        if (empty($this->param)) {
            return $this->message = 'Пароль не может быть пустым';
        }

        if (empty($this->param['repeatedPassword'])) {
            return $this->message = 'Введите пароль повторно';
        }

        if ($this->param['password'] !== $this->param['repeatedPassword']) {
            return $this->message = 'Пароли не совпадают';
        }

        return $this->isValid = true;
    }

}