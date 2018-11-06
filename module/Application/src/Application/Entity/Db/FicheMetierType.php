<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;

class FicheMetierType {

    /** @var int */
    private $id;
    /** @var string */
    private $libelle;
    /** @var string */
    private $missionsPrincipales;

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
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return FicheMetierType
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string
     */
    public function getMissionsPrincipales()
    {
        return $this->missionsPrincipales;
    }

    /**
     * @param string $missionsPrincipales
     * @return FicheMetierType
     */
    public function setMissionsPrincipales($missionsPrincipales)
    {
        $this->missionsPrincipales = $missionsPrincipales;
        return $this;
    }
}