<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\DbImportableAwareTrait;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use Carriere\Entity\Db\Corps;
use Carriere\Entity\Db\Correspondance;
use Carriere\Entity\Db\Grade;
use DateTime;
use Structure\Entity\Db\Structure;

/**
 * Données synchronisées depuis Octopus :
 * - pas de setter sur les données ainsi remontées
 */
class AgentEchelon {
    use DbImportableAwareTrait;

    /** @var int */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var int */
    private  $echelon;
    /** @var DateTime */
    private $date;

    /**
     * @return int|null
     */
    public function getId() : ?int
    {
        return $this->id;
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
     * @return AgentEchelon
     */
    public function setAgent(Agent $agent): AgentEchelon
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getEchelon(): ?int
    {
        return $this->echelon;
    }

    /**
     * @param int $echelon
     * @return AgentEchelon
     */
    public function setEchelon(int $echelon): AgentEchelon
    {
        $this->echelon = $echelon;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return AgentEchelon
     */
    public function setDate(DateTime $date): AgentEchelon
    {
        $this->date = $date;
        return $this;
    }
}