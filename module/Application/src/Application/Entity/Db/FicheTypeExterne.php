<?php

namespace Application\Entity\Db;

/**
 * Class FicheTypeExterne
 * Lien entre une fiche métier et les fiches types
 *
 * NB : $activites stocke la liste des activités conservées dans un string donts les ids sont concaténés avec ';'
 */

class FicheTypeExterne {

    /** @var integer */
    private $id;
    /** @var FicheMetier */
    private $ficheMetier;
    /** @var FicheMetierType */
    private $ficheType;
    /** @var integer */
    private $quotite;
    /** @var boolean */
    private $estPrincipale;
    /** @var string */
    private $activites;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FicheMetier
     */
    public function getFicheMetier()
    {
        return $this->ficheMetier;
    }

    /**
     * @param FicheMetier $ficheMetier
     * @return FicheTypeExterne
     */
    public function setFicheMetier($ficheMetier)
    {
        $this->ficheMetier = $ficheMetier;
        return $this;
    }

    /**
     * @return FicheMetierType
     */
    public function getFicheType()
    {
        return $this->ficheType;
    }

    /**
     * @param FicheMetierType $ficheType
     * @return FicheTypeExterne
     */
    public function setFicheType($ficheType)
    {
        $this->ficheType = $ficheType;
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
     * @return FicheTypeExterne
     */
    public function setQuotite($quotite)
    {
        $this->quotite = $quotite;
        return $this;
    }

    /**
     * @return bool
     */
    public function getPrincipale()
    {
        return $this->estPrincipale;
    }

    /**
     * @param bool $estPrincipale
     * @return FicheTypeExterne
     */
    public function setPrincipale($estPrincipale)
    {
        $this->estPrincipale = $estPrincipale;
        return $this;
    }

    /**
     * @return string
     */
    public function getActivites()
    {
        return $this->activites;
    }

    /**
     * @param string $activites
     * @return FicheTypeExterne
     */
    public function setActivites($activites)
    {
        $this->activites = $activites;
        return $this;
    }

}