<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use Application\Entity\Db\Traits\HasSourceTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FormationGroupe implements HistoriqueAwareInterface, HasDescriptionInterface, HasSourceInterface
{
    use HasSourceTrait;
    use HasDescriptionTrait;
    use HistoriqueAwareTrait;

    private ?int $id = -1;
    private ?string $libelle = null;
    private ?int $ordre = 0;
    private Collection $formations;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

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

    public function getOrdre() : ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?string $ordre) : void
    {
        $this->ordre = $ordre;
    }

    /**
     * @return Formation[]
     */
    public function getFormations() : array
    {
        return $this->formations->toArray();
    }

}