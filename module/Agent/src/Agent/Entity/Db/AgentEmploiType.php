<?php

namespace Agent\Entity\Db;

use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use Carriere\Entity\Db\EmploiType;
use UnicaenSynchro\Entity\Db\IsSynchronisableInterface;
use UnicaenSynchro\Entity\Db\IsSynchronisableTrait;

class AgentEmploiType implements HasPeriodeInterface, IsSynchronisableInterface
{
    use HasPeriodeTrait;
    use IsSynchronisableTrait;

    private ?int $id = -1;
    private ?Agent $agent = null;
    private ?EmploiType $emploiType = null;

    /** Données : cette donnée est synchronisée >> par conséquent, il n'y a que des getters */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function getEmploiType(): ?EmploiType
    {
        return $this->emploiType;
    }

    /** @noinspection PhpUnused */
    public function toStringEmploiType(): string
    {
        $texte  = "Emploi-type ";
        if ($this->getEmploiType()) {
            $texte .= $this->getEmploiType()->getLibelleLong() . " (". $this->getEmploiType()->getCode() . ")";
        } else $texte .= "<span class='missing-data'>non renseigné</span>";
        $texte .= " (du";
        if ($this->getDateDebut()) {
            $texte .= " " . $this->getDateDebut()->format('d/m/Y');
        } else $texte .= "<span class='missing-data'>non renseignée</span>";
        $texte .= " au ";
        if ($this->getDateFin()) {
            $texte .= " " . $this->getDateFin()->format('d/m/Y');
        } else $texte .= "<span class='missing-data'>non renseignée</span>";
        $texte .= ") ";
        return $texte;
    }


}

