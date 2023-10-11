<?php

namespace Application\Entity\Db;

use Metier\Entity\Db\Domaine;

class DomaineRepartition {

    private ?int $id = null;
    private ?FicheTypeExterne $ficheMetierExterne = null;
    private ?Domaine $domaine = null;
    private ?int $quotite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFicheMetierExterne(): ?FicheTypeExterne
    {
        return $this->ficheMetierExterne;
    }

    public function setFicheMetierExterne(FicheTypeExterne $ficheMetierExterne): void
    {
        $this->ficheMetierExterne = $ficheMetierExterne;
    }

    public function getDomaine(): ?Domaine
    {
        return $this->domaine;
    }

    public function setDomaine(Domaine $domaine): void
    {
        $this->domaine = $domaine;
    }

    public function getQuotite(): ?int
    {
        return $this->quotite;
    }

    public function setQuotite(int $quotite): void
    {
        $this->quotite = $quotite;
    }
}