<?php

namespace Application\Entity\Db;

use Application\Entity\HasAgentInterface;
use DateTime;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class AgentMissionSpecifique implements HistoriqueAwareInterface, HasAgentInterface {
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
    /** @var DateTime */
    private $dateDebut;
    /** @var DateTime */
    private $dateFin;
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
     * @return MissionSpecifique
     */
    public function getMission()
    {
        return $this->mission;
    }

    /**
     * @param MissionSpecifique $mission
     * @return AgentMissionSpecifique
     */
    public function setMission($mission)
    {
        $this->mission = $mission;
        return $this;
    }

    /**
     * @return Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * @param Structure $structure
     * @return AgentMissionSpecifique
     */
    public function setStructure($structure)
    {
        $this->structure = $structure;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @param DateTime $dateDebut
     * @return AgentMissionSpecifique
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * @param DateTime $dateFin
     * @return AgentMissionSpecifique
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    /**
     * @return float
     */
    public function getDecharge()
    {
        return $this->decharge;
    }

    /**
     * @param float $decharge
     * @return AgentMissionSpecifique
     */
    public function setDecharge($decharge)
    {
        $this->decharge = $decharge;
        return $this;
    }

    /**
     * @param DateTime $date
     * @return boolean
     */
    public function estEnCours(DateTime $date = null) {

        if ($this->estHistorise()) return false;

        if ($date === null) $date = $this->getDateTime();
        if ($this->getDateDebut() > $date) return false;
        if ($this->getDateFin() !== null AND $this->getDateFin() < $date) return false;
        return true;
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
        $texte  = "";
        if ($this->getDecharge() !== null) {
            $texte .= "de ";
            $texte .= $this->getDecharge();
            $texte .= " heures";
            return $texte;
        }
        return "aucune";
    }
}
