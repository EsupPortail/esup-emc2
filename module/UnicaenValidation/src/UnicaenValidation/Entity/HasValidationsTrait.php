<?php

namespace UnicaenValidation\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenValidation\Entity\Db\ValidationInstance;

trait HasValidationsTrait {

    /** @var ArrayCollection  (ValidationInstance) */
    private $validations;

    /**
     * @return ValidationInstance[]
     */
    public function getValidations() : array
    {
        return $this->validations->toArray();
    }

    /**
     * @param ValidationInstance $validation
     * @return void
     */
    public function addValidation(ValidationInstance $validation) : void
    {
        $this->validations->add($validation);
    }

    /**
     * @param ValidationInstance $validation
     * @return void
     */
    public function removeValidation(ValidationInstance $validation) : void
    {
        $this->validations->removeElement($validation);
    }

    /**
     * @param string $typeCode
     * @return array
     */
    public function getValidationsByTypeCode(string $typeCode) : array
    {
        $result = [];
        /** @var ValidationInstance $validation */
        foreach ($this->validations as $validation) {
            if ($validation->getType()->getCode() === $typeCode AND $validation->estNonHistorise()) $result[] = $validation;
        }
        return $result;
    }
    /**
     * @param string $typeCode
     * @return ValidationInstance|null
     */
    public function getValidationByTypeCode(string $typeCode) : ?ValidationInstance
    {
        /** @var ValidationInstance $validation */
        foreach ($this->validations as $validation) {
            if ($validation->getType()->getCode() === $typeCode AND $validation->estNonHistorise()) return $validation;
        }
        return null;
    }



}