<?php

namespace Application\Entity\Db;

use Application\Entity\HasAgentInterface;
use DateTime;
use Formation\Entity\Db\Formation;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;
use UnicaenValidation\Entity\ValidableAwareTrait;
use UnicaenValidation\Entity\ValidableInterface;

class AgentFormation implements ValidableInterface, HistoriqueAwareInterface, HasAgentInterface {
    use HistoriqueAwareTrait;
    use ValidableAwareTrait;

    /** @var integer */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var Formation */
    private $formation;
    /** @var DateTime */
    private $date;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Agent|null
     */
    public function getAgent() : ?Agent
    {
        return $this->agent;
    }

    /**
     * @param Agent|null $agent
     * @return AgentFormation
     */
    public function setAgent(?Agent $agent) : AgentFormation
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return Formation
     */
    public function getFormation()
    {
        return $this->formation;
    }

    /**
     * @param Formation $formation
     * @return AgentFormation
     */
    public function setFormation($formation)
    {
        $this->formation = $formation;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return AgentFormation
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function estValide()
    {
        $validation = $this->getValidation();
        if ($validation === null) return false;
        if ($validation->getValeur() !== null) return false;
        return true;
    }

}