<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;

class FicheMetierType {

    /** @var int */
    private $id;
    /** @var Metier */
    private $metier;
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
     * @return Metier
     */
    public function getMetier()
    {
        return $this->metier;
    }

    /**
     * @param Metier $metier
     * @return FicheMetierType
     */
    public function setMetier($metier)
    {
        $this->metier = $metier;
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