<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FicheposteActiviteDescriptionRetiree implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var FichePoste */
    private $fichePoste;
    /** @var FicheMetier */
    private $ficheMetier;
    /** @var Activite */
    private $activite;
    /** @var ActiviteDescription */
    private $description;

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
     * @return FicheposteActiviteDescriptionRetiree
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
     * @return FicheposteActiviteDescriptionRetiree
     */
    public function setFicheMetier($ficheMetier)
    {
        $this->ficheMetier = $ficheMetier;
        return $this;
    }

    /**
     * @return Activite
     */
    public function getActivite()
    {
        return $this->activite;
    }

    /**
     * @param Activite $activite
     * @return FicheposteActiviteDescriptionRetiree
     */
    public function setActivite($activite)
    {
        $this->activite = $activite;
        return $this;
    }

    /**
     * @return ActiviteDescription
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param ActiviteDescription $description
     * @return FicheposteActiviteDescriptionRetiree
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param Activite $activite
     * @param FicheposteActiviteDescriptionRetiree[] $retirees
     * @return FicheposteActiviteDescriptionRetiree[]
     */
    static public function filtrer($activite, $retirees)
    {
        $result = [];
        foreach ($retirees as $retiree) {
            if ($activite->getId() === $retiree->getDescription()->getActivite()->getId()) $result[] = $retiree;
        }
        return $result;
    }
}