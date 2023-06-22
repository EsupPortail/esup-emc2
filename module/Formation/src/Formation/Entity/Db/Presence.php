<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Entity\Db\Traits\HasSourceTrait;
use RuntimeException;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Presence implements HistoriqueAwareInterface, HasSourceInterface
{
    use HistoriqueAwareTrait;
    use HasSourceTrait;

    const PRESENCE_NON_RENSEIGNEE = "NON_RENSEIGNEE";
    const PRESENCE_PRESENCE = "PRESENCE";
    const PRESENCE_ABSENCE_JUSTIFIEE = "ABSENCE_JUSTIFIEE";
    const PRESENCE_ABSENCE_NON_JUSTIFIEE = "ABSENCE_NON_JUSTIFIEE";

    private int $id = -1;
    private ?Seance $journee = null;
    private ?FormationInstanceInscrit $inscrit = null;
    private ?string $presenceType = null;
    private string $statut = self::PRESENCE_NON_RENSEIGNEE;
    private ?string $commentaire = null;
    private ?string $source = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getJournee(): ?Seance
    {
        return $this->journee;
    }

    public function setJournee(?Seance $journee): void
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
        return $this->statut === Presence::PRESENCE_PRESENCE;
    }

    public function tooglePresence(): void
    {
        switch ($this->statut) {
            case Presence::PRESENCE_NON_RENSEIGNEE :
                $this->statut = Presence::PRESENCE_PRESENCE;
                break;
            case Presence::PRESENCE_PRESENCE :
                $this->statut = Presence::PRESENCE_ABSENCE_JUSTIFIEE;
                break;
            case Presence::PRESENCE_ABSENCE_JUSTIFIEE :
                $this->statut = Presence::PRESENCE_ABSENCE_NON_JUSTIFIEE;
                break;
            case Presence::PRESENCE_ABSENCE_NON_JUSTIFIEE :
                $this->statut = Presence::PRESENCE_NON_RENSEIGNEE;
                break;
            default :
                throw new RuntimeException("Valeur [" . $this->statut . "] non attendue");
        }
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
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