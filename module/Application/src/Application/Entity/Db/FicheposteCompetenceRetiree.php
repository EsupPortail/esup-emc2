<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class FicheposteCompetenceRetiree implements HistoriqueAwareInterface{
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var FichePoste */
    private $fichePoste;
    /** @var FicheMetier */
    private $ficheMetier;
    /** @var Competence */
    private $competence;

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
     * @return FicheposteCompetenceRetiree
     */
    public function setFichePoste($fichePoste)
    {
        $this->fichePoste = $fichePoste;
        return $this;
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
     * @return FicheposteCompetenceRetiree
     */
    public function setFicheMetier($ficheMetier)
    {
        $this->ficheMetier = $ficheMetier;
        return $this;
    }

    /**
     * @return Competence
     */
    public function getCompetence()
    {
        return $this->competence;
    }

    /**
     * @param Competence $competence
     * @return FicheposteCompetenceRetiree
     */
    public function setCompetence($competence)
    {
        $this->competence = $competence;
        return $this;
    }


}