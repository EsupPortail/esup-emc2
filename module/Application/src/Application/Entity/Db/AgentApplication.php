<?php

namespace Application\Entity\Db;

use DateTime;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class AgentApplication {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var Application */
    private $application;
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
     * @return AgentApplication
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param Application $application
     * @return AgentApplication
     */
    public function setApplication($application)
    {
        $this->application = $application;
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
     * @return AgentApplication
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
     * @return AgentApplication
     */
    public function setValidation($validation)
    {
        $this->validation = $validation;
        return $this;
    }


}