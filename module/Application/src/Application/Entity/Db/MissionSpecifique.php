<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;

class MissionSpecifique {

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var ArrayCollection */
    private $agents;

    public function __construct()
    {
        $this->agents = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return MissionSpecifique
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return MissionSpecifique
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return Agent[]
     */
    public function getAgents()
    {
        return $this->agents->toArray();
    }

    /**
     * @param Agent $agent
     * @return MissionSpecifique
     */
    public function addAgent($agent)
    {
        $this->agents->add($agent);
        return $this;
    }

    /**
     * @param Agent $agent
     * @return MissionSpecifique
     */
    public function removeAgent($agent)
    {
        $this->agents->removeElement($agent);
        return $this;
    }
}