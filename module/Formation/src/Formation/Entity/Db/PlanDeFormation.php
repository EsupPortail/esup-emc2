<?php

namespace Formation\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class PlanDeFormation {

    private ?int $id = -1;
    private ?string $annee = null;
    private Collection $formations;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee(?string $annee): void
    {
        $this->annee = $annee;
    }

    /**
     * @return Formation[]
     */
    public function getFormations(): array
    {
        return $this->formations->toArray();
    }

    /**
     * Bircole pour rendre compatible le formulaire de sélection des enseignements
     */
    public function getFormationListe(): array
    {
        return $this->formations->toArray();
    }
}