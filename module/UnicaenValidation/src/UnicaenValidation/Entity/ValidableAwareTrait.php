<?php

namespace UnicaenValidation\Entity;

use UnicaenValidation\Entity\Db\ValidationInstance;

trait ValidableAwareTrait{

    private ?ValidationInstance $validation = null;

    public function getValidation() : ?ValidationInstance
    {
        return $this->validation;
    }

    public function setValidation(?ValidationInstance $instance) : void
    {
        $this->validation = $instance;
    }
}