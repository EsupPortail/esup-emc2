<?php

namespace UnicaenValidation\Entity;

use UnicaenValidation\Entity\Db\ValidationInstance;

trait ValidableAwareTrait{

    /** @var ValidationInstance */
    private $validation;

    /**
     * @return ValidationInstance
     */
    public function getValidation() {
        return $this->validation;
    }

    /**
     * @param ValidationInstance $instance
     * @return self
     */
    public function setValidation($instance) {
        $this->validation = $instance;
        return $this;
    }
}