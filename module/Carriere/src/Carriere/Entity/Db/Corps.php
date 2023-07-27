<?php

namespace Carriere\Entity\Db;

use Application\Entity\Db\AgentGrade;
use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\DbImportableAwareTrait;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/** Elements synchronisés ********************************/

class Corps implements HasPeriodeInterface
{
    use DbImportableAwareTrait;
    use HasPeriodeTrait;

    private ?int $id = null;
    private ?string $code = null;
    private ?string $libelleCourt = null;
    private ?string $libelleLong = null;
    private ?string $categorie = null;
    private ?DateTime $histo = null;
    private ?Niveau $niveau = null;
    private ?NiveauEnveloppe $niveaux = null;

    private Collection $agentGrades;

    public function __construct()
    {
        $this->agentGrades = new ArrayCollection();
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function setId(int $id) : void
    {
        $this->id = $id;
    }

    public function getCode() : ?string
    {
        return $this->code;
    }

    public function getLibelleCourt() : ?string
    {
        return $this->libelleCourt;
    }

    public function getLibelleLong(): ?string
    {
        return $this->libelleLong;
    }

    public function getCategorie() : ?string
    {
        return $this->categorie;
    }

    public function getNiveau() : ?Niveau
    {
        return $this->niveau;
    }

    public function getNiveaux(): ?NiveauEnveloppe
    {
        return $this->niveaux;
    }

    public function setNiveaux(?NiveauEnveloppe $niveaux): void
    {
        $this->niveaux = $niveaux;
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

    /**
     * @return string
     */
    public function generateTooltip() : string
    {
        $text  = "Libelle court : <strong>". $this->getLibelleCourt() . "</strong>";
        $text .= "<br/>";
        $text .= "Libelle long : <strong>". $this->getLibelleLong() . "</strong>";
        $text .= "<br/>";
        $text .= "Code : <strong>". $this->getCode() . "</strong>";
        return $text;
    }
}