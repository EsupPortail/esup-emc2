<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use Application\Entity\Db\Traits\HasSourceTrait;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FormationGroupe implements HistoriqueAwareInterface, HasDescriptionInterface, HasSourceInterface
{
    use HasSourceTrait;
    use HasDescriptionTrait;
    use HistoriqueAwareTrait;

    private ?int $id = -1;
    private ?string $libelle = null;
    private ?string $couleur = null;
    /** @var string */
    private $ordre;
    /** @var ArrayCollection (Formation) */
    private $formations;

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

    public function getLibelle() : ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle) : void
    {
        $this->libelle = $libelle;
    }

    public function getCouleur() : ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur) : void
    {
        $this->couleur = $couleur;
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