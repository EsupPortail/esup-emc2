<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Utilisateur\Entity\Db\User;

class Agent {
    use ImportableAwareTrait;

    /** @var string */
    private $id;
    /** @var string */
    private $sourceName;
    /** @var integer */
    private $sourceId;
    /** @var string */
    private $prenom;
    /** @var string */
    private $nomUsuel;
    /** @var User */
    private $utilisateur;

    /** @var ArrayCollection (AgentStatut)*/
    private $statuts;
    /** @var ArrayCollection (MissionSpecifique) */
    private $missions;

    public function __construct()
    {
        $this->statuts = new ArrayCollection();
        $this->missions = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSourceName()
    {
        return $this->sourceName;
    }

    /**
     * @param string $sourceName
     * @return Agent
     */
    public function setSourceName($sourceName)
    {
        $this->sourceName = $sourceName;
        return $this;
    }

    /**
     * @return int
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * @param int $sourceId
     * @return Agent
     */
    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     * @return Agent
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @return string
     */
    public function getNomUsuel()
    {
        return $this->nomUsuel;
    }

    /**
     * @param string $nomUsuel
     * @return Agent
     */
    public function setNomUsuel($nomUsuel)
    {
        $this->nomUsuel = $nomUsuel;
        return $this;
    }

    /**
     * @return string
     */
    public function getDenomination()
    {
        return $this->getPrenom().' '.$this->getNomUsuel();

    }
    /**
     * @return User
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * @param User $utilisateur
     * @return Agent
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    /**
     * @return AgentStatut[]
     */
    public function getStatuts() {
        return $this->statuts->toArray();
    }

    /**
     * @param AgentStatut
     * @return Agent
     */
    public function addStatut($statut)
    {
        $this->statuts->add($statut);
        return $this;
    }

    /**
     * @param AgentStatut
     * @return Agent
     */
    public function removeStatut($statut)
    {
        $this->statuts->removeElement($statut);
        return $this;
    }

    /**
     * @return MissionSpecifique[]
     */
    public function getMissions() {
        return $this->missions->toArray();
    }

    /**
     * @param MissionSpecifique
     * @return Agent
     */
    public function addMission($mission)
    {
        $this->missions->add($mission);
        return $this;
    }

    /**
     * @param MissionSpecifique
     * @return Agent
     */
    public function removeMission($mission)
    {
        $this->missions->removeElement($mission);
        return $this;
    }
}