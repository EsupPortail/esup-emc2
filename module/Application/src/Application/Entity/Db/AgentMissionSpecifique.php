<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use Application\Entity\HasAgentInterface;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class AgentMissionSpecifique implements HistoriqueAwareInterface, HasAgentInterface, HasPeriodeInterface {
    use HasPeriodeTrait;
    use HistoriqueAwareTrait;
    use DateTimeAwareTrait;

    /** @var integer */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var MissionSpecifique */
    private $mission;
    /** @var Structure */
    private $structure;
    /** @var float */
    private $decharge;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Agent|null
     */
    public function getAgent() : ?Agent
    {
        return $this->agent;
    }

    /**
     * @param Agent|null $agent
     * @return AgentMissionSpecifique
     */
    public function setAgent(?Agent $agent) : AgentMissionSpecifique
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return MissionSpecifique|null
     */
    public function getMission() : ?MissionSpecifique
    {
        return $this->mission;
    }

    /**
     * @param MissionSpecifique|null $mission
     * @return AgentMissionSpecifique
     */
    public function setMission(?MissionSpecifique $mission) : AgentMissionSpecifique
    {
        $this->mission = $mission;
        return $this;
    }

    /**
     * @return Structure|null
     */
    public function getStructure() : ?Structure
    {
        return $this->structure;
    }

    /**
     * @param Structure|null $structure
     * @return AgentMissionSpecifique
     */
    public function setStructure(?Structure $structure) : AgentMissionSpecifique
    {
        $this->structure = $structure;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getDecharge() : ?float
    {
        return $this->decharge;
    }

    /**
     * @param float $decharge
     * @return AgentMissionSpecifique
     */
    public function setDecharge(float $decharge) : AgentMissionSpecifique
    {
        $this->decharge = $decharge;
        return $this;
    }

    /** FONCTIONS DEDIEES AUX AFFICHAGES **************************************************************/

    public function getPeriode()
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

    public function getDechargeTexte()
    {
        if ($this->getDecharge() === null) {
            return "0";
        }
        return $this->getDecharge();
    }
}
