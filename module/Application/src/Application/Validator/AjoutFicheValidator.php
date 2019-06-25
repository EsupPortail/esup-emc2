<?php

namespace Application\Validator;

use Zend\Validator\AbstractValidator;

class AjoutFicheValidator extends AbstractValidator {

    public function isValid($value)
    {
        var_dump($value);
        return true;
    }
}