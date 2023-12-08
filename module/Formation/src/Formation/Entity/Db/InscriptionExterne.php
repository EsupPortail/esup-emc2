<?php

namespace Formation\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class InscriptionExterne implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?FormationInstance $session = null;
    private ?StagiaireExterne $stagiaire = null;
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSession(): ?FormationInstance
    {
        return $this->session;
    }

    public function setSession(?FormationInstance $session): void
    {
        $this->session = $session;
    }

    public function getStagiaire(): ?StagiaireExterne
    {
        return $this->stagiaire;
    }

    public function setStagiaire(?StagiaireExterne $stagiaire): void
    {
        $this->stagiaire = $stagiaire;
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