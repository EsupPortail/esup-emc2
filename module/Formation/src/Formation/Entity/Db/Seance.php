<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Entity\Db\Traits\HasSourceTrait;
use DateTime;
use RuntimeException;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Seance implements HistoriqueAwareInterface, HasSourceInterface
{
    use HistoriqueAwareTrait;
    use HasSourceTrait;

    const TYPE_SEANCE = "SEANCE";
    const TYPE_VOLUME = "VOLUME";
    const TYPES = [
        Seance::TYPE_SEANCE => "Séance de formation",
        Seance::TYPE_VOLUME => "Volume horaire",
    ];

    private ?int $id = null;
    private ?Session $instance = null;
    private string $type = self::TYPE_SEANCE;

    //seance
    private ?DateTime $jour = null;
    private ?string $debut = null;
    private ?string $fin = null;
    private ?string $oldLieu = null;
    private ?Lieu $lieu = null;
    private ?string $lien = null;

    //volume
    private ?float $volume = null;
    private ?DateTime $volumeDebut = null;
    private ?DateTime $volumeFin = null;

    private ?string $remarque = null;
    private ?string $source = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstance(): ?Session
    {
        return $this->instance;
    }

    public function setInstance(Session $instance): void
    {
        $this->instance = $instance;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /** DONNEES RELATIVES AUX SEANCES **************************** */
    public function getJour(): ?DateTime
    {
        return $this->jour;
    }

    public function setJour(?DateTime $jour): void
    {
        $this->jour = $jour;
    }

    public function getDebut(): ?string
    {
        return $this->debut;
    }

    public function setDebut(?string $debut): void
    {
        $this->debut = $debut;
    }

    public function getFin(): ?string
    {
        return $this->fin;
    }

    public function setFin(?string $fin): void
    {
        $this->fin = $fin;
    }

    public function getOldLieu(): ?string
    {
        return $this->oldLieu;
    }

    public function setOldLieu(?string $oldLieu): void
    {
        $this->oldLieu = $oldLieu;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): void
    {
        $this->lieu = $lieu;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(?string $lien): void
    {
        $this->lien = $lien;
    }

    public function getDateDebut(): null|DateTime
    {
        if ($this->getDebut() === null) return null;
        switch ($this->type) {
            case Seance::TYPE_SEANCE :
                $asString = $this->getJour()->format('d/m/Y') . " " . $this->getDebut();
                return DateTime::createFromFormat('d/m/Y H:i', $asString);
            case Seance::TYPE_VOLUME :
                $asString = $this->getVolumeDebut()->format('d/m/Y') . " 08:00";
                return DateTime::createFromFormat('d/m/Y H:i', $asString);
        }
        throw new RuntimeException("Le type de seance est inconnu");
    }

    public function getDateFin(): null|DateTime
    {
        if ($this->getDebut() === null) return null;
        switch ($this->type) {
            case Seance::TYPE_SEANCE :
                $asString = $this->getJour()->format('d/m/Y') . " " . $this->getFin();
                return DateTime::createFromFormat('d/m/Y H:i', $asString);
            case Seance::TYPE_VOLUME :
                $asString = $this->getVolumeFin()->format('d/m/Y') . " 08:00";
                return DateTime::createFromFormat('d/m/Y H:i', $asString);
        }
        throw new RuntimeException("Le type de seance est inconnu");
    }

    /** DONNEE RELATIVE AU "VOLUME" *****************************/

    public function getVolume(): ?float
    {
        return $this->volume;
    }

    public function setVolume(?float $volume): void
    {
        $this->volume = $volume;
    }

    public function getVolumeDebut(): ?DateTime
    {
        return $this->volumeDebut;
    }

    public function setVolumeDebut(?DateTime $volumeDebut): void
    {
        $this->volumeDebut = $volumeDebut;
    }

    public function getVolumeFin(): ?DateTime
    {
        return $this->volumeFin;
    }

    public function setVolumeFin(?DateTime $volumeFin): void
    {
        $this->volumeFin = $volumeFin;
    }

    /** AUTRE ****************************************************** */

    public function getRemarque(): ?string
    {
        return $this->remarque;
    }

    public function setRemarque(?string $remarque): void
    {
        $this->remarque = $remarque;
    }

    public static function hasIntersectionNonNulle(Seance $seance, Seance $sessionSeance): bool
    {
        $sDebut = $seance->getDateDebut(); $sFin = $seance->getDateFin();
        $ssDebut = $sessionSeance->getDateDebut(); $ssFin = $sessionSeance->getDateFin();
        if ($sDebut <= $ssDebut AND $sFin >= $ssDebut) return true;
        if ($sDebut <= $ssFin AND $sFin >= $ssFin) return true;
        if ($sDebut >= $ssDebut AND $sFin <= $ssFin) return true;
        if ($sDebut <= $ssDebut AND $sFin >= $ssFin) return true;
        return false;
    }
}