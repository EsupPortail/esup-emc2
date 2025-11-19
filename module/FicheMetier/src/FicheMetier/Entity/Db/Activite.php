<?php

namespace FicheMetier\Entity\Db;


use Metier\Entity\Db\Referentiel;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Activite implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?string $libelle = null;
    private ?string $description = null;
    private ?Referentiel $referentiel = null;
    private ?string $idOrig = null;
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

    public function getReferentiel(): ?Referentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiel $referentiel): void
    {
        $this->referentiel = $referentiel;
    }

    public function getIdOrig(): ?string
    {
        return $this->idOrig;
    }

    public function setIdOrig(?string $idOrig): void
    {
        $this->idOrig = $idOrig;
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
