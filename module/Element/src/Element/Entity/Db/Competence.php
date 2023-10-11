<?php

namespace Element\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Competence implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    const SOURCE_REFERENS3 = 'REFERENS3';
    const SOURCE_EMC2 = 'EMC2';

    private ?int $id = null;
    private ?string $libelle = null;
    private ?string $description = null;
    private ?CompetenceType $type = null;
    private ?CompetenceTheme $theme = null;
    private ?CompetenceReferentiel $referentiel = null;
    private ?string $source = null;
    private ?string $idSource = null;

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

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description) : void
    {
        $this->description = $description;
    }

    public function getType() : ?CompetenceType
    {
        return $this->type;
    }

    public function setType(?CompetenceType $type) : void
    {
        $this->type = $type;
    }

    public function getTheme() : ?CompetenceTheme
    {
        return $this->theme;
    }

    public function setTheme(?CompetenceTheme $theme) : void
    {
        $this->theme = $theme;
    }

    public function getReferentiel(): ?CompetenceReferentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?CompetenceReferentiel $referentiel): void
    {
        $this->referentiel = $referentiel;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): Competence
    {
        $this->source = $source;
        return $this;
    }

    public function getIdSource(): ?string
    {
        return $this->idSource;
    }

    public function setIdSource(?string $idSource): Competence
    {
        $this->idSource = $idSource;
        return $this;
    }


}