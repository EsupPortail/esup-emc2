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
    /** @var FichePoste */
    private $fichePoste;
    /** @var FicheMetier */
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
     * @return FichePoste
     */
    public function getFichePoste()
    {
        return $this->fichePoste;
    }

    /**
     * @param FichePoste $fichePoste
     * @return FicheTypeExterne
     */
    public function setFichePoste($fichePoste)
    {
        $this->fichePoste = $fichePoste;
        return $this;
    }

    /**
     * @return FicheMetier
     */
    public function getFicheType()
    {
        return $this->ficheType;
    }

    /**
     * @param FicheMetier $ficheType
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