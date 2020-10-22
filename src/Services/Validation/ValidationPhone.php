<?php


namespace App\Services\Validation;


class ValidationPhone extends AbstractValidation
{
    public function validate()
    {
        if (empty($this->param)) {
            return $this->message = 'Телефон не может быть пустым';
        }

        if (!preg_match('/^[0-9]{6,15}$/ui', $this->param)) {
            return $this->message = 'Неправильно введен телефон';
    }

        return $this->isValid = true;
    }

}