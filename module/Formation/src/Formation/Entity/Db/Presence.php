<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Entity\Db\Traits\HasSourceTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Presence implements HistoriqueAwareInterface, HasSourceInterface
{
    use HistoriqueAwareTrait;
    use HasSourceTrait;

    private int $id = -1;
    private ?Seance $journee = null;
    private ?FormationInstanceInscrit $inscrit = null;
    private ?string $presenceType = null;
    private bool $presenceTemoin = false;
    private ?string $commentaire = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getJournee(): ?Seance
    {
        return $this->journee;
    }

    public function setJournee(?Seance $journee) : void
    {
        $this->journee = $journee;
    }

    public function getInscrit(): ?FormationInstanceInscrit
    {
        return $this->inscrit;
    }

    public function setInscrit(?FormationInstanceInscrit $inscrit): void
    {
        $this->inscrit = $inscrit;
    }

    public function getPresenceType(): ?string
    {
        return $this->presenceType;
    }

    public function setPresenceType(string $presenceType): void
    {
        $this->presenceType = $presenceType;
    }

    public function isPresent(): bool
    {
        return $this->presenceTemoin;
    }

    public function setPresent(bool $presenceTemoin): void
    {
        $this->presenceTemoin = $presenceTemoin;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): void
    {
        $this->commentaire = $commentaire;
    }

}