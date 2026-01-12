<?php

namespace Metier\Entity\Db;

use Carriere\Entity\Db\Correspondance;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FamilleProfessionnelle implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?string $libelle = null;
    private ?Correspondance $correspondance = null;
    private ?int $position = null;

    private Collection $metiers;

    public function __construct()
    {
        $this->metiers = new ArrayCollection();
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getLibelle() : ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle) : void
    {
        $this->libelle = $libelle;
    }

    /** @return Metier[] */
    public function getMetiers() : array
    {
        return $this->metiers->toArray();
    }

    public function getCorrespondance(): ?Correspondance
    {
        return $this->correspondance;
    }

    public function setCorrespondance(?Correspondance $correspondance): void
    {
        $this->correspondance = $correspondance;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    public function __toString() : string
    {
        return $this->getLibelle();
    }
}