<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Entity\Db\Traits\HasSourceTrait;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FormationGroupe implements HistoriqueAwareInterface, HasSourceInterface
{
    use HasSourceTrait;
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
    public function getId() : int
    {
        return $this->id;
    }

    /** /!\ NB: utilise pour creer le groupe : sans groupe
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getLibelle() : ?string
    {
        return $this->libelle;
    }

    /**
     * @param string|null $libelle
     * @return FormationGroupe
     */
    public function setLibelle(?string $libelle) : FormationGroupe
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCouleur() : ?string
    {
        return $this->couleur;
    }

    /**
     * @param string|null $couleur
     * @return FormationGroupe
     */
    public function setCouleur(?string $couleur) : FormationGroupe
    {
        $this->couleur = $couleur;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrdre() : ?string
    {
        return $this->ordre;
    }

    /**
     * @param string|null $ordre
     * @return FormationGroupe
     */
    public function setOrdre(?string $ordre) : FormationGroupe
    {
        $this->ordre = $ordre;
        return $this;
    }

    /**
     * @return Formation[]
     */
    public function getFormations() : array
    {
        return $this->formations->toArray();
    }

    /**
     * @param Formation $formation
     * @return FormationGroupe
     */
    public function addFormation(Formation $formation) : FormationGroupe
    {
        $this->formations->add($formation);
        return $this;
    }

    /**
     * @param Formation $formation
     * @return FormationGroupe
     */
    public function removeFormation(Formation $formation) : FormationGroupe
    {
        $this->formations->removeElement($formation);
        return $this;
    }

    /**
     * @param Formation $formation
     * @return boolean
     */
    public function hasFormation(Formation $formation) : bool
    {
        return $this->formations->contains($formation);
    }


}