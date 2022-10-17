<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Entity\Db\Traits\HasSourceTrait;
use DateTime;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Seance implements HistoriqueAwareInterface, HasSourceInterface
{
    use HistoriqueAwareTrait;
    use HasSourceTrait;

    const TYPE_SEANCE = "SEANCE";
    const TYPE_VOLUME = "VOLUME";

    private ?int $id = null;
    private ?FormationInstance $instance = null;
    private string $type = self::TYPE_SEANCE;
    private ?DateTime $jour = null;
    private ?string $debut = null;
    private ?string $fin = null;
    private ?float $volume = null;
    private ?string $lieu = null;
    private ?string $remarque = null;

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

    /** DONNEE RELATIVE AU "VOLUME" *****************************/

    public function getVolume(): ?float
    {
        return $this->volume;
    }

    public function setVolume(?float $volume): void
    {
        $this->volume = $volume;
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