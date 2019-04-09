<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareTrait;

class StructureType {
    use HistoriqueAwareTrait;

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
     * @return StructureType
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
     * @return StructureType
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }


}