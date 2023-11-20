<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Domaine implements HistoriqueAwareInterface, HasDescriptionInterface
{
    use HistoriqueAwareTrait;
    use HasDescriptionTrait;

    const MAX_ORDRE = 9999;

    private ?int $id = -1;
    private ?string $libelle = null;
    private ?int $ordre = null;
    private Collection $formations;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): void
    {
        $this->ordre = $ordre;
    }

    /** @return Formation[] */
    public function getFormations(): array
    {
        return $this->formations->toArray();
    }

}