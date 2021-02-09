<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\DbImportableAwareTrait;
use DateTime;

/**
 * Données synchronisées depuis Octopus :
 * - pas de setter sur les données ainsi remontées
 */
class AgentGrade {
    use DbImportableAwareTrait;

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
    /** @var DateTime */
    private $dateDebut;
    /** @var DateTime */
    private $dateFin;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Agent
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param Agent $agent
     * @return AgentGrade
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * @param Structure $structure
     * @return AgentGrade
     */
    public function setStructure($structure)
    {
        $this->structure = $structure;
        return $this;
    }

    /**
     * @return Corps
     */
    public function getCorps()
    {
        return $this->corps;
    }

    /**
     * @param Corps $corps
     * @return AgentGrade
     */
    public function setCorps($corps)
    {
        $this->corps = $corps;
        return $this;
    }

    /**
     * @return Grade
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * @param Grade $grade
     * @return AgentGrade
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;
        return $this;
    }

    /**
     * @return Correspondance
     */
    public function getBap()
    {
        return $this->bap;
    }

    /**
     * @param Correspondance $bap
     * @return AgentGrade
     */
    public function setBap($bap)
    {
        $this->bap = $bap;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @param DateTime $dateDebut
     * @return AgentGrade
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * @param DateTime $dateFin
     * @return AgentGrade
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
        return $this;
    }

}