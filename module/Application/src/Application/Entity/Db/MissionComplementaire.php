<?php

namespace Application\Entity\Db;

class MissionComplementaire {

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
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
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return MissionComplementaire
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
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
     * @return MissionComplementaire
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }
}