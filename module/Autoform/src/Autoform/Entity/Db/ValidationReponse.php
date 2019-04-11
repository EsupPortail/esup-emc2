<?php

namespace Autoform\Entity\Db;

class ValidationReponse {

    /** @var integer */
    private $id;
    /** @var Validation */
    private $validation;
    /** @var FormulaireReponse */
    private $formulaireReponse;
    /** @var boolean */
    private $value;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Validation
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * @param Validation $validation
     * @return ValidationReponse
     */
    public function setValidation($validation)
    {
        $this->validation = $validation;
        return $this;
    }

    /**
     * @return FormulaireReponse
     */
    public function getReponse()
    {
        return $this->formulaireReponse;
    }

    /**
     * @param FormulaireReponse $formulaireReponse
     * @return ValidationReponse
     */
    public function setReponse($formulaireReponse)
    {
        $this->formulaireReponse = $formulaireReponse;
        return $this;
    }

    /**
     * @return bool
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param bool $value
     * @return ValidationReponse
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }



}