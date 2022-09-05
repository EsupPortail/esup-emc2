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

    private ?int $id = null;
    private ?FormationInstance $instance = null;
    private ?DateTime $jour = null;
    private ?string $debut = null;
    private ?string $fin = null;
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

    public function getJour() : ?DateTime
    {
        return $this->jour;
    }

    public function setJour(DateTime $jour) : void
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

    public function getRemarque() : ?string
    {
        return $this->remarque;
    }

    public function setRemarque(?string $remarque) : void
    {
        $this->remarque = $remarque;
    }

}