<?php

namespace Agent\Entity\Db;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use UnicaenSynchro\Entity\Db\IsSynchronisableInterface;
use UnicaenSynchro\Entity\Db\IsSynchronisableTrait;

class AgentEchelon implements HasPeriodeInterface, IsSynchronisableInterface
{
    use HasPeriodeTrait;
    use IsSynchronisableTrait;

    private ?int $id = -1;
    private ?Agent $agent = null;
    private ?int $echelon = null;

    /** Données : cette donnée est synchronisée >> par conséquent, il n'y a que des getters */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function getEchelon(): ?int
    {
        return $this->echelon;
    }

}