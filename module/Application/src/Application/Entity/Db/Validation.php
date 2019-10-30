<?php

namespace Application\Entity\Db;

class ValidationType {

    const FICHE_METIER_RELECTURE = 'FICHE_METIER_RELECTURE';

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $libelle;

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
}

