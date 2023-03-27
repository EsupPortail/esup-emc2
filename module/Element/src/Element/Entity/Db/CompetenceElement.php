<?php

namespace Element\Entity\Db;

use Element\Entity\Db\Interfaces\HasNiveauInterface;
use Element\Entity\Db\Traits\HasNiveauTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use UnicaenValidation\Entity\ValidableAwareTrait;
use UnicaenValidation\Entity\ValidableInterface;

class CompetenceElement implements HistoriqueAwareInterface, ValidableInterface, HasNiveauInterface {
    use HistoriqueAwareTrait;
    use ValidableAwareTrait;
    use HasNiveauTrait;

    private ?int $id = null;
    private ?Competence $competence = null;
    private ?string $commentaire = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): void
    {
        $this->competence = $competence;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    public function getLibelle() : string
    {
        return ($this->competence)?$this->competence->getLibelle():"";
    }

    public function getObjet() : ?Competence
    {
        return $this->getCompetence();
    }
}