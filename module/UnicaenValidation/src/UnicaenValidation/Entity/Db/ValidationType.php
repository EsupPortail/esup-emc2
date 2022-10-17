<?php

namespace UnicaenValidation\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class ValidationType {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $libelle;
    /** @var bool */
    private $refusable;

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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return ValidationType
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return ValidationType
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRefusable()
    {
        return $this->refusable;
    }

    /**
     * @param bool $refusable
     * @return ValidationType
     */
    public function setRefusable($refusable)
    {
        $this->refusable = $refusable;
        return $this;
    }
}