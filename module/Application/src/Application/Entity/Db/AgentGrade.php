<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\DbImportableAwareTrait;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use Carriere\Entity\Db\Corps;
use Carriere\Entity\Db\Correspondance;
use Carriere\Entity\Db\Grade;
use Structure\Entity\Db\Structure;

/**
 * Données synchronisées depuis Octopus :
 * - pas de setter sur les données ainsi remontées
 */
class AgentGrade implements HasPeriodeInterface {
    use DbImportableAwareTrait;
    use HasPeriodeTrait;

    /** @var string */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var Structure */
    private $structure;
    /** @var Corps */
    private $corps;
    /** @var Grade */
    private $grade;
    /** @var Correspondance */
    private $bap;

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return Agent
     */
    public function getAgent() : Agent
    {
        return $this->agent;
    }

    /**
     * @param Agent $agent
     * @return AgentGrade
     */
    public function setAgent(Agent $agent) : AgentGrade
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return Structure|null
     */
    public function getStructure() : ?Structure
    {
        return $this->structure;
    }

    /**
     * @param Structure $structure
     * @return AgentGrade
     */
    public function setStructure(Structure $structure) : AgentGrade
    {
        $this->structure = $structure;
        return $this;
    }

    /**
     * @return Corps|null
     */
    public function getCorps() : ?Corps
    {
        return $this->corps;
    }

    /**
     * @param Corps $corps
     * @return AgentGrade
     */
    public function setCorps(Corps $corps) : AgentGrade
    {
        $this->corps = $corps;
        return $this;
    }

    /**
     * @return Grade|null
     */
    public function getGrade() : ?Grade
    {
        return $this->grade;
    }

    /**
     * @param Grade $grade
     * @return AgentGrade
     */
    public function setGrade(Grade $grade) : AgentGrade
    {
        $this->grade = $grade;
        return $this;
    }

    /**
     * @return Correspondance|null
     */
    public function getBap() : ?Correspondance
    {
        return $this->bap;
    }

    /**
     * @param Correspondance $bap
     * @return AgentGrade
     */
    public function setBap(Correspondance $bap) : AgentGrade
    {
        $this->bap = $bap;
        return $this;
    }

}