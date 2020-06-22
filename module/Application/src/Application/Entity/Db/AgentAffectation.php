<?php

namespace Application\Entity\Db;

use DateTime;

class AgentAffectation {

    /** @var integer */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var Structure */
    private $structure;
    /** @var DateTime */
    private $dateDebut;
    /** @var DateTime */
    private $dateFin;
    /** @var string */
    private $idOrig;
    /** @var string */
    private $principale;

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
     * @return Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * @return DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @return DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * @return string
     */
    public function getIdOrig()
    {
        return $this->idOrig;
    }

    /**
     * @return boolean
     */
    public function isPrincipale()
    {
        return ($this->principale === 'O');
    }


}