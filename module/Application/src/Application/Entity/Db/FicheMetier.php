<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareTrait;

class FicheMetier
{
    use HistoriqueAwareTrait;

    /** @var int */
    private $id;
    /** @var string */
    private $libelle;
    /** @var Affectation */
    private $affectation;
    /** @var Agent */
    private $agent;

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
     * @return FicheMetier
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return Affectation
     */
    public function getAffectation()
    {
        return $this->affectation;
    }

    /**
     * @param Affectation $affectation
     * @return FicheMetier
     */
    public function setAffectation($affectation)
    {
        $this->affectation = $affectation;
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
     * @return FicheMetier
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
        return $this;
    }


}