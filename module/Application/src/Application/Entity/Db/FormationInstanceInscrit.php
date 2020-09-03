<?php

namespace Application\Entity\Db;

use Application\Entity\HasAgentInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class FormationInstanceInscrit implements HistoriqueAwareInterface, HasAgentInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var FormationInstance */
    private $instance;
    /** @var Agent */
    private $agent;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FormationInstance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstanceInscrit
     */
    public function setInstance($instance)
    {
        $this->instance = $instance;
        return $this;
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
     * @return FormationInstanceInscrit
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
        return $this;
    }

}