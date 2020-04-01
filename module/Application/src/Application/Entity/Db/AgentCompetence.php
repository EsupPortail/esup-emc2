<?php

namespace Application\Entity\Db;

use DateTime;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;
use UnicaenValidation\Entity\ValidableAwareTrait;
use UnicaenValidation\Entity\ValidableInterface;

class AgentCompetence implements ValidableInterface {
    use HistoriqueAwareTrait;
    use ValidableAwareTrait;

    /** @var integer */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var Competence */
    private $competence;
    /** @var string */
    private $niveau;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return AgentCompetence
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return AgentCompetence
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return Competence
     */
    public function getCompetence()
    {
        return $this->competence;
    }

    /**
     * @param Competence $competence
     * @return AgentCompetence
     */
    public function setCompetence($competence)
    {
        $this->competence = $competence;
        return $this;
    }

    /**
     * @return string
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * @param string $niveau
     * @return AgentCompetence
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;
        return $this;
    }

}