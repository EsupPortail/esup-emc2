<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Utilisateur\Entity\HistoriqueAwareTrait;

class FormationTheme {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var ArrayCollection (Formation) */
    private $formations;

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
     * @return FormationTheme
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return Formation[]
     */
    public function getFormations()
    {
        return $this->formations->toArray();
    }

    /**
     * @param Formation $formation
     * @return FormationTheme
     */
    public function addFormation($formation)
    {
        $this->formations->add($formation);
        return $this;
    }
    /**
     * @param Formation $formation
     * @return FormationTheme
     */
    public function removeFormation($formation)
    {
        $this->formations->removeElement($formation);
        return $this;
    }

    /**
     * @param Formation $formation
     * @return boolean
     */
    public function hasFormation($formation)
    {
        return $this->formations->contains($formation);
    }


}