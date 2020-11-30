<?php

namespace Formation\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class FormationGroupe implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var string */
    private $couleur;
    /** @var string */
    private $ordre;
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
     * @return FormationGroupe
     */
    public function setLibelle(string $libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * @param string|null $couleur
     * @return FormationGroupe
     */
    public function setCouleur(?string $couleur)
    {
        $this->couleur = $couleur;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * @param string|null $ordre
     * @return FormationGroupe
     */
    public function setOrdre(?string $ordre)
    {
        $this->ordre = $ordre;
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
     * @return FormationGroupe
     */
    public function addFormation(Formation $formation)
    {
        $this->formations->add($formation);
        return $this;
    }

    /**
     * @param Formation $formation
     * @return FormationGroupe
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