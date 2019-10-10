<?php

namespace Application\Entity\Db;

use DateTime;
use Utilisateur\Entity\Db\User;
use Utilisateur\Entity\HistoriqueAwareTrait;

class AgentCompetence {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var Competence */
    private $competence;
    /** @var string */
    private $niveau;
    /** @var User */
    private $validateur;
    /** @var DateTime */
    private $dateValidation;

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

    /**
     * @return User
     */
    public function getValidateur()
    {
        return $this->validateur;
    }

    /**
     * @param User $validateur
     * @return AgentCompetence
     */
    public function setValidateur($validateur)
    {
        $this->validateur = $validateur;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->dateValidation;
    }

    /**
     * @param DateTime $date
     * @return AgentCompetence
     */
    public function setDate($date)
    {
        $this->dateValidation = $date;
        return $this;
    }




}