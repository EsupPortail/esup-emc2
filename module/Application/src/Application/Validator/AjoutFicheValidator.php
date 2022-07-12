<?php

namespace Application\Validator;

use Laminas\Validator\AbstractValidator;

class AjoutFicheValidator extends AbstractValidator {

    public function isValid($value)
    {
        var_dump($value);
        return true;
    }
}