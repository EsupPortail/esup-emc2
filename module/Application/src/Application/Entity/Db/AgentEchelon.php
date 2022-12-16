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
class AgentEchelon implements HasPeriodeInterface {
    use DbImportableAwareTrait;
    use HasPeriodeTrait;

    private ?int $id = -1;
    private ?Agent $agent = null;
    private ?int $echelon = null;

    public function getId() : ?int
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