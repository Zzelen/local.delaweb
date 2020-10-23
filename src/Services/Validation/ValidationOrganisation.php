<?php


namespace App\Services\Validation;


class ValidationOrganisation extends AbstractValidation
{
    public function validate()
    {
        if (empty($this->param)) {
            return $this->message = 'Организация не может быть пустой';
        }
        return $this->isValid = true;
    }

}