<?php

namespace FicheMetier\Entity\Db;

use Carriere\Entity\Db\FonctionType;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class CodeEmploiType implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?FicheMetier $fichemetier = null;
    private ?FonctionType $codefonction = null;
    private ?string $complement = null;

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

    public function getCodeFonction(): ?FonctionType
    {
        return $this->codefonction;
    }

    public function setCodeFonction(?FonctionType $codeFonction): void
    {
        $this->codefonction = $codeFonction;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): void
    {
        $this->complement = $complement;
    }

    public function prettyPrint(): string
    {
        return "<string>".($this->getCodeFonction()?$this->getCodeFonction()->getCode():"nill").$this->getComplement()."</string>";
    }
}
