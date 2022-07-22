<?php

namespace EntretienProfessionnel\Entity\Db;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use Structure\Entity\Db\Structure;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Delegue implements HistoriqueAwareInterface, HasDescriptionInterface {

    use HistoriqueAwareTrait;
    use HasDescriptionTrait;

    /** @var int */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var Structure*/
    private $structure;
    /** @var Campagne */
    private $campagne;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Agent
     */
    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    /**
     * @param Agent $agent
     * @return Delegue
     */
    public function setAgent(Agent $agent): Delegue
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return Structure
     */
    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    /**
     * @param Structure $structure
     * @return Delegue
     */
    public function setStructure(Structure $structure): Delegue
    {
        $this->structure = $structure;
        return $this;
    }

    /**
     * @return Campagne
     */
    public function getCampagne(): ?Campagne
    {
        return $this->campagne;
    }

    /**
     * @param Campagne $campagne
     * @return Delegue
     */
    public function setCampagne(Campagne $campagne): Delegue
    {
        $this->campagne = $campagne;
        return $this;
    }



}