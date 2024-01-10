<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use Application\Entity\HasAgentInterface;
use MissionSpecifique\Entity\Db\MissionSpecifique;
use Structure\Entity\Db\Structure;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class AgentMissionSpecifique implements HistoriqueAwareInterface, HasAgentInterface, HasPeriodeInterface {
    use HasPeriodeTrait;
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?Agent $agent = null;
    private ?MissionSpecifique $mission = null;
    private ?Structure $structure = null;
    private ?float $decharge = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgent() : ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent) : void
    {
        $this->agent = $agent;
    }

    public function getMission() : ?MissionSpecifique
    {
        return $this->mission;
    }

    public function setMission(?MissionSpecifique $mission) : void
    {
        $this->mission = $mission;
    }

    public function getStructure() : ?Structure
    {
        return $this->structure;
    }

    public function setStructure(?Structure $structure) : void
    {
        $this->structure = $structure;
    }

    public function getDecharge() : ?float
    {
        return $this->decharge;
    }

    public function setDecharge(?float $decharge) : void
    {
        $this->decharge = $decharge;
    }

    /** FONCTIONS DEDIEES AUX AFFICHAGES **************************************************************/

    public function getPeriode(): string
    {
        $texte  = "";
        if ($this->getDateDebut() !== null AND $this->getDateFin() !== null) {
            $texte .= "du ";
            $texte .= $this->getDateDebut()->format('d/m/Y');
            $texte .= " au ";
            $texte .= $this->getDateFin()->format('d/m/Y');
            return $texte;
        }
        if ($this->getDateDebut() !== null AND $this->getDateFin() === null) {
            $texte .= "à partir du ";
            $texte .= $this->getDateDebut()->format('d/m/Y');
            return $texte;
        }
        return "sur une période non déterminée";
    }

    public function getDechargeTexte(): string
    {
        if ($this->getDecharge() === null) {
            return "0";
        }
        return $this->getDecharge();
    }
}
