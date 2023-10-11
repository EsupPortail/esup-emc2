<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use DateTime;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FormationAbonnement implements HistoriqueAwareInterface, HasDescriptionInterface
{
    use HistoriqueAwareTrait;
    use HasDescriptionTrait;

    private ?int $id = null;
    private ?Formation $formation = null;
    private ?Agent $agent = null;
    private ?DateTime $dateInscription = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(Formation $formation): void
    {
        $this->formation = $formation;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(Agent $agent): void
    {
        $this->agent = $agent;
    }

    public function getDateInscription(): DateTime
    {
        return $this->dateInscription;
    }

    public function setDateInscription(DateTime $dateInscription): void
    {
        $this->dateInscription = $dateInscription;
    }

}