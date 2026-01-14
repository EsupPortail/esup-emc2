<?php

namespace EmploiRepere\Entity\Db;

use FicheMetier\Entity\Db\CodeFonction;
use FicheMetier\Entity\Db\FicheMetier;

class EmploiRepereCodeFonctionFicheMetier {

    private ?int $id = null;
    private ?EmploiRepere $emploiRepere = null;
    private ?CodeFonction $codeFonction = null;
    private ?FicheMetier $ficheMetier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmploiRepere(): ?EmploiRepere
    {
        return $this->emploiRepere;
    }

    public function setEmploiRepere(?EmploiRepere $emploiRepere): void
    {
        $this->emploiRepere = $emploiRepere;
    }

    public function getCodeFonction(): ?CodeFonction
    {
        return $this->codeFonction;
    }

    public function setCodeFonction(?CodeFonction $codeFonction): void
    {
        $this->codeFonction = $codeFonction;
    }

    public function getFicheMetier(): ?FicheMetier
    {
        return $this->ficheMetier;
    }

    public function setFicheMetier(?FicheMetier $ficheMetier): void
    {
        $this->ficheMetier = $ficheMetier;
    }

}
