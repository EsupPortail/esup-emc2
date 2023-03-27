<?php

namespace Element\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class ApplicationTheme implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?string $libelle = null;
    private ?int $ordre = null;
    private Collection $applications;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
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

    public function getOrdre() : ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre) : void
    {
        $this->ordre = $ordre;
    }

    /** @return Application[] */
    public function getApplications() : array
    {
        return $this->applications->toArray();
    }
}