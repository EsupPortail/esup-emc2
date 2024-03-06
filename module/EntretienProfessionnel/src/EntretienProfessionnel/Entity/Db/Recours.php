<?php

namespace EntretienProfessionnel\Entity\Db;

use DateTime;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Recours implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?EntretienProfessionnel $entretien = null;
    private ?DateTime $dateProcedure = null;
    private ?string $commentaire = null;
    private bool $entretienModifiable = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntretien(): ?EntretienProfessionnel
    {
        return $this->entretien;
    }

    public function setEntretien(?EntretienProfessionnel $entretien): void
    {
        $this->entretien = $entretien;
    }

    public function getDateProcedure(): ?DateTime
    {
        return $this->dateProcedure;
    }

    public function setDateProcedure(?DateTime $dateProcedure): void
    {
        $this->dateProcedure = $dateProcedure;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    public function isEntretienModifiable(): bool
    {
        return $this->entretienModifiable;
    }

    public function setEntretienModifiable(bool $entretienModifiable): void
    {
        $this->entretienModifiable = $entretienModifiable;
    }

}