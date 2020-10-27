<?php


namespace App\Services\Validation;


class ValidationSurname extends AbstractValidation
{
    public function validate()
    {
        if (empty($this->param)) {
            return $this->message = 'Фамилия не может быть пустой';
        }

        if (preg_match('/\W/ui', $this->param)) {
            return $this->message = 'Нельзя использовать пробелы и спецсимволы';
        }

        return $this->isValid = true;
    }

}