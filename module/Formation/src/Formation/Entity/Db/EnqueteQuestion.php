<?php

namespace Formation\Entity\Db;


use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class EnqueteQuestion implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = -1;
    private ?string $libelle = null;
    private ?string $description = null;
    private ?int $ordre = null;
    private ?EnqueteCategorie $categorie = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): void
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

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): void
    {
        $this->ordre = $ordre;
    }

    public function getCategorie(): ?EnqueteCategorie
    {
        return $this->categorie;
    }

    public function setCategorie(?EnqueteCategorie $categorie): void
    {
        $this->categorie = $categorie;
    }
}