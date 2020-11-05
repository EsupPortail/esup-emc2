<?php

namespace Application\Entity\Db;

use Application\Entity\HasAgentInterface;
use Application\Entity\HasApplicationInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;
use UnicaenValidation\Entity\ValidableAwareTrait;
use UnicaenValidation\Entity\ValidableInterface;

class AgentApplication implements ValidableInterface, HistoriqueAwareInterface, HasAgentInterface, HasApplicationInterface {
    use HistoriqueAwareTrait;
    use ValidableAwareTrait;

    /** @var integer */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var Application */
    private $application;
    /** @var string */
    private $type;
    /** @var integer */
    private $annee;

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
     * @return AgentApplication
     */
    public function setAgent(?Agent $agent) : AgentApplication
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return Application|null
     */
    public function getApplication() : ?Application
    {
        return $this->application;
    }

    /**
     * @param Application|null $application
     * @return AgentApplication
     */
    public function setApplication(?Application $application) : AgentApplication
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
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * @param int $annee
     * @return AgentApplication
     */
    public function setAnnee(int $annee)
    {
        $this->annee = $annee;
        return $this;
    }


}