<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use DateTime;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FormationAbonnement implements HistoriqueAwareInterface, HasDescriptionInterface {
    use HistoriqueAwareTrait;
    use HasDescriptionTrait;

    /** @var int */
    private  $id;
    /** @var Formation  */
    private $formation;
    /** @var Agent */
    private $agent;
    /** @var DateTime */
    private $dateInscription;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Formation|null
     */
    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    /**
     * @param Formation $formation
     */
    public function setFormation(Formation $formation): void
    {
        $this->formation = $formation;
    }

    /**
     * @return Agent|null
     */
    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    /**
     * @param Agent $agent
     */
    public function setAgent(Agent $agent): void
    {
        $this->agent = $agent;
    }

    /**
     * @return DateTime
     */
    public function getDateInscription(): DateTime
    {
        return $this->dateInscription;
    }

    /**
     * @param DateTime $dateInscription
     */
    public function setDateInscription(DateTime $dateInscription): void
    {
        $this->dateInscription = $dateInscription;
    }

}