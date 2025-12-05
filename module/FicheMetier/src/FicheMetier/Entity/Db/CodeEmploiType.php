<?php

namespace FicheMetier\Entity\Db;

use Carriere\Entity\Db\NiveauFonction;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class CodeEmploiType implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?FicheMetier $fichemetier = null;
    private ?NiveauFonction $codefonction = null;
    private ?string $correspondance = null;
    private ?string $niveau = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getFichemetier(): ?FicheMetier
    {
        return $this->fichemetier;
    }

    public function setFichemetier(?FicheMetier $fichemetier): void
    {
        $this->fichemetier = $fichemetier;
    }

    public function getCodeFonction(): ?NiveauFonction
    {
        return $this->codefonction;
    }

    public function setCodeFonction(?NiveauFonction $codeFonction): void
    {
        $this->codefonction = $codeFonction;
    }

    public function getCorrespondance(): ?string
    {
        return $this->correspondance;
    }

    public function setCorrespondance(?string $correspondance): void
    {
        $this->correspondance = $correspondance;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(?string $niveau): void
    {
        $this->niveau = $niveau;
    }

    public function prettyPrint(): string
    {
        return
            ($this->getCodeFonction()?$this->getCodeFonction()->getCode():"nill").
            ($this->getCorrespondance()??"?").
            ($this->getNiveau()??"?");
    }
}
