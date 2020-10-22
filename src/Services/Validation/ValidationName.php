<?php


namespace App\Services\Validation;


class ValidationName extends AbstractValidation
{
    public function validate()
    {
        if (empty($this->param)) {
            return $this->message = 'Имя не может быть пустым';
        }

        if (preg_match('/\\s\\W/ui', $this->param)) {
            return $this->message = 'Нельзя использовать пробелы и спецсимволы';
        }

        return $this->isValid = true;
    }

}