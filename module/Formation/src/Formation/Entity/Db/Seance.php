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
    private ?FormationInstance $instance = null;
    private string $type = self::TYPE_SEANCE;

    //seance
    private ?DateTime $jour = null;
    private ?string $debut = null;
    private ?string $fin = null;
    private ?string $lieu = null;

    //volume
    private ?float $volume = null;
    private ?DateTime $volumeDebut = null;
    private ?DateTime $volumeFin = null;

    private ?string $remarque = null;
    private ?string $source = null;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getInstance() : ?FormationInstance
    {
        return $this->instance;
    }

    public function setInstance(FormationInstance $instance) : void
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
    public function getJour() : ?DateTime
    {
        return $this->jour;
    }

    public function setJour(?DateTime $jour) : void
    {
        $this->jour = $jour;
    }

    public function getDebut() : ?string
    {
        return $this->debut;
    }

    public function setDebut(?string $debut) : void
    {
        $this->debut = $debut;
    }

    public function getFin() : ?string
    {
        return $this->fin;
    }

    public function setFin(?string $fin) : void
    {
        $this->fin = $fin;
    }

    public function getLieu() : ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu) : void
    {
        $this->lieu = $lieu;
    }

    public function getDateDebut(): null|DateTime
    {
        if ($this->getDebut() === null) return null;
        switch($this->type) {
            case Seance::TYPE_SEANCE :
                $asString = $this->getJour()->format('d/m/y')." ".$this->getDebut();
                return DateTime::createFromFormat('d/m/Y H:i', $asString);
            case Seance::TYPE_VOLUME :
                $asString = $this->getJour()->format('d/m/y')." 08:00";
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

    public function getRemarque() : ?string
    {
        return $this->remarque;
    }

    public function setRemarque(?string $remarque) : void
    {
        $this->remarque = $remarque;
    }

}