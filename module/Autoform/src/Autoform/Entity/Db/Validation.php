<?php

namespace Autoform\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Validation {
    use HistoriqueAwareTrait;

    const TYPE_SUMPPS       = "SUMPPS";
    const TYPE_MEDECIN      = "MEDECIN";
    const TYPE_COMPOSANTE   = "COMPOSANTE";

    /** @var integer */
    private $id;
    /** @var string */
    private $type;
    /** @var FormulaireInstance */
    private $instance;

    /** @var string */
    private $typeValidation;
    /** @var FormulaireInstance */
    private $reference;


    /** @var ArrayCollection */
    private $reponses;


    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Validation
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return FormulaireInstance
     */
    public function getFormulaireInstance()
    {
        return $this->instance;
    }

    /**
     * @param FormulaireInstance $instance
     * @return Validation
     */
    public function setFormulaireInstance($instance)
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * @return ValidationReponse[]
     */
    public function getReponses() {
        return $this->reponses->toArray();
    }

    /**
     * @param ValidationReponse $reponse
     * @return Validation
     */
    public function addReponse($reponse)
    {
        $this->reponses->add($reponse);
        return $this;
    }

    /**
     * @return FormulaireInstance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * @param FormulaireInstance $instance
     * @return Validation
     */
    public function setInstance($instance)
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * @return string
     */
    public function getTypeValidation()
    {
        return $this->typeValidation;
    }

    /**
     * @param string $typeValidation
     * @return Validation
     */
    public function setTypeValidation($typeValidation)
    {
        $this->typeValidation = $typeValidation;
        return $this;
    }

    /**
     * @return FormulaireInstance
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param FormulaireInstance $reference
     * @return Validation
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }

}