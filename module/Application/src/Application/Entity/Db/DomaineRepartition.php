<?php

namespace Application\Entity\Db;


class DomaineRepartition {

    private ?int $id = null;
    private ?FicheTypeExterne $ficheMetierExterne = null;
//    private ?Domaine $domaine = null;
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

    public function getDomaine(): null
    {
//        return $this->domaine;
        return null;
    }

    public function setDomaine(): void
    {
//        $this->domaine = $domaine;
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