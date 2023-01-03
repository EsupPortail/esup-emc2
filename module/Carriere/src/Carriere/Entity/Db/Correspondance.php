<?php

namespace Carriere\Entity\Db;

use Application\Entity\Db\AgentGrade;
use Application\Entity\Db\Traits\DbImportableAwareTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Correspondance  {
    use DbImportableAwareTrait;

    private ?int  $id = null;
    private ?string $categorie = null;
    private ?string $libelleCourt = null;
    private ?string $libelleLong = null;
    private ?DateTime $histo = null;
    private Collection $agentGrades;
    private ?CorrespondanceType $type = null;
    private ?DateTime $dateOuverture = null;
    private ?DateTime $dateFermeture = null;

    public function __construct()
    {
        $this->agentGrades = new ArrayCollection();
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getCategorie() : ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie) : void
    {
        $this->categorie = $categorie;
    }

    public function getLibelleCourt() : ?string
    {
        return $this->libelleCourt;
    }

    public function setLibelleCourt(?string $libelleCourt) : void
    {
        $this->libelleCourt = $libelleCourt;
    }

    public function getLibelleLong() : ?string
    {
        return $this->libelleLong;
    }

    public function setLibelleLong(?string $libelleLong) : void
    {
        $this->libelleLong = $libelleLong;
    }

    public function getType(): ?CorrespondanceType
    {
        return $this->type;
    }

    public function setType(?CorrespondanceType $type): void
    {
        $this->type = $type;
    }

    public function getDateOuverture(): ?DateTime
    {
        return $this->dateOuverture;
    }

    public function setDateOuverture(?DateTime $dateOuverture): void
    {
        $this->dateOuverture = $dateOuverture;
    }

    public function getDateFermeture(): ?DateTime
    {
        return $this->dateFermeture;
    }

    public function setDateFermeture(?DateTime $dateFermeture): void
    {
        $this->dateFermeture = $dateFermeture;
    }

    public function getHisto() : ?DateTime
    {
        return $this->histo;
    }

    public function setHisto($histo) : void
    {
        $this->histo = $histo;
    }

    /**
     * @return AgentGrade[]
     */
    public function getAgentGrades() : array
    {
        return $this->agentGrades->toArray();
    }

    public function __toString() : string
    {
        return $this->getLibelleCourt();
    }

    public function generateTooltip() : string
    {
        $text  = "Libelle court : <strong>". $this->getLibelleCourt() . "</strong>";
        $text .= "<br/>";
        $text .= "Libelle long : <strong>". $this->getLibelleLong() . "</strong>";
        return $text;
    }

    public function isHisto(?DateTime $date = null) : bool
    {
        if ($date === null) $date = new DateTime();
        return ($this->histo !== null AND $date < $this->histo);
    }

}