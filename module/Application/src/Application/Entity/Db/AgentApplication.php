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
    /** @var string */
    private $type;
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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return AgentApplication
     */
    public function setType($type)
    {
        $this->type = $type;
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