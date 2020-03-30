<?php

namespace Application\Entity\Db;

use DateTime;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class AgentFormation {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var Formation */
    private $formation;
    /** @var DateTime */
    private $date;
    /** @var integer */
    private $validation;

    /**
     * @return int
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
     * @return AgentFormation
     */
    public function setAgent($agent)
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

    /**
     * @return int
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * @param int $validation
     * @return AgentFormation
     */
    public function setValidation($validation)
    {
        $this->validation = $validation;
        return $this;
    }


}