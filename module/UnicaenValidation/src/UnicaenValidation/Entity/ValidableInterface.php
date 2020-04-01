<?php

namespace UnicaenValidation\Entity;

use UnicaenValidation\Entity\Db\ValidationInstance;

interface ValidableInterface {

    /**
     * @return ValidationInstance
     */
    public function getValidation();

    /**
     * @param ValidationInstance $instance
     * @return self
     */
    public function setValidation(ValidationInstance $instance);

}