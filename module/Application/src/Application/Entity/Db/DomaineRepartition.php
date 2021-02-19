<?php

namespace Application\Entity\Db;

use Metier\Entity\Db\Domaine;

/** QUID historiser cela ? */
class DomaineRepartition {

    /** @var integer */
    private $id;
    /** @var FicheTypeExterne */
    private $ficheMetierExterne;
    /** @var Domaine */
    private $domaine;
    /** @var Integer */
    private $quotite;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FicheTypeExterne
     */
    public function getFicheMetierExterne()
    {
        return $this->ficheMetierExterne;
    }

    /**
     * @param FicheTypeExterne $ficheMetierExterne
     * @return DomaineRepartition
     */
    public function setFicheMetierExterne(FicheTypeExterne $ficheMetierExterne)
    {
        $this->ficheMetierExterne = $ficheMetierExterne;
        return $this;
    }

    /**
     * @return Domaine
     */
    public function getDomaine()
    {
        return $this->domaine;
    }

    /**
     * @param Domaine|null $domaine
     * @return DomaineRepartition
     */
    public function setDomaine(Domaine $domaine)
    {
        $this->domaine = $domaine;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuotite()
    {
        return $this->quotite;
    }

    /**
     * @param int $quotite
     * @return DomaineRepartition
     */
    public function setQuotite($quotite)
    {
        $this->quotite = $quotite;
        return $this;
    }
}