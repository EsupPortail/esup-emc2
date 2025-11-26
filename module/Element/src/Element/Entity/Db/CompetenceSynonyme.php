<?php

namespace Element\Entity\Db;

class CompetenceSynonyme {

    private ?int $id = null;
    private ?Competence $competence = null;
    private ?string $libelle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): void
    {
        $this->competence = $competence;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): void
    {
        $this->libelle = $libelle;
    }


}