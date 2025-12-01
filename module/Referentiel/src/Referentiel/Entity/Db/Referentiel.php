<?php

namespace Referentiel\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Referentiel implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?string $libelleCourt = null;
    private ?string $libelleLong = null;
    private ?string $couleur = null;
    private ?string $description = null;

    //linkage vers les autres elements

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getLibelleCourt(): ?string
    {
        return $this->libelleCourt;
    }

    public function setLibelleCourt(?string $libelleCourt): void
    {
        $this->libelleCourt = $libelleCourt;
    }

    public function getLibelleLong(): ?string
    {
        return $this->libelleLong;
    }

    public function setLibelleLong(?string $libelleLong): void
    {
        $this->libelleLong = $libelleLong;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): void
    {
        $this->couleur = $couleur;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }


}
