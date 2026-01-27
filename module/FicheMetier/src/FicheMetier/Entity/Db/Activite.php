<?php

namespace FicheMetier\Entity\Db;


use Referentiel\Entity\Db\Interfaces\HasReferenceInterface;
use Referentiel\Entity\Db\Traits\HasReferenceTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Activite implements HistoriqueAwareInterface, HasReferenceInterface {
    use HistoriqueAwareTrait;
    use HasReferenceTrait;

    const ACTIVITE_HEADER_ID = 'Id';
    const ACTIVITE_HEADER_LIBELLE = 'Libellé';
    const ACTIVITE_HEADER_DESCRIPTION = 'Description';
    const ACTIVITE_HEADER_CODES_EMPLOITYPE = 'Codes Emploi Type';
    const ACTIVITE_HEADER_CODES_FONCTION = 'Codes Fonction';

    private ?int $id = null;
    private ?string $libelle = null;
    private ?string $description = null;
    private ?string $codesFicheMetier = null;
    private ?string $codesFonction = null;

    private ?string $raw = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getCodesFicheMetier(): ?string
    {
        return $this->codesFicheMetier;
    }

    public function setCodesFicheMetier(?string $codesFicheMetier): void
    {
        $this->codesFicheMetier = $codesFicheMetier;
    }

    public function getCodesFonction(): ?string
    {
        return $this->codesFonction;
    }

    public function setCodesFonction(?string $codesFonction): void
    {
        $this->codesFonction = $codesFonction;
    }

    public function setRaw(?string $raw): void
    {
        $this->raw = $raw;
    }

    public function getRaw(): ?string
    {
        return $this->raw;
    }

}
