<?php

namespace Formation\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class FormationTheme implements HistoriqueAwareInterface
{
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
    public function setLibelle(string $libelle)
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
    public function addFormation(Formation $formation)
    {
        $this->formations->add($formation);
        return $this;
    }

    /**
     * @param Formation $formation
     * @return FormationTheme
     */
    public function removeFormation(Formation $formation)
    {
        $this->formations->removeElement($formation);
        return $this;
    }

    /**
     * @param Formation $formation
     * @return boolean
     */
    public function hasFormation(Formation $formation)
    {
        return $this->formations->contains($formation);
    }


}