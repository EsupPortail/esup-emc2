<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class FicheposteFormationRetiree {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var FichePoste */
    private $fichePoste;
    /** @var FicheMetier */
    private $ficheMetier;
    /** @var Formation */
    private $formation;

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
     * @return FicheposteFormationRetiree
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
     * @return FicheposteFormationRetiree
     */
    public function setFicheMetier($ficheMetier)
    {
        $this->ficheMetier = $ficheMetier;
        return $this;
    }

    /**
     * @return Formation
     */
    public function getFormation()
    {
        return $this->formation;
    }

    /**
     * @param Formation $formation
     * @return FicheposteFormationRetiree
     */
    public function setFormation($formation)
    {
        $this->formation = $formation;
        return $this;
    }


}