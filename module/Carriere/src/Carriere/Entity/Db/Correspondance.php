<?php

namespace Carriere\Entity\Db;

use Agent\Entity\Db\AgentGrade;
use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenSynchro\Entity\Db\IsSynchronisableInterface;
use UnicaenSynchro\Entity\Db\IsSynchronisableTrait;

class Correspondance implements HasPeriodeInterface, IsSynchronisableInterface
{
    use IsSynchronisableTrait;
    use HasPeriodeTrait;

    private ?int $id = null;
    private ?string $categorie = null;
    private ?string $libelleCourt = null;
    private ?string $libelleLong = null;
    private ?DateTime $histo = null;
    private Collection $agentGrades;
    private ?CorrespondanceType $type = null;
    private ?DateTime $dateFermeture = null;

    public function __construct()
    {
        $this->agentGrades = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): void
    {
        $this->categorie = $categorie;
    }

    public function getLibelleCourt(): ?string
    {
        return $this->libelleCourt;
    }

    public function getLibelleLong(): ?string
    {
        return $this->libelleLong;
    }

    public function getType(): ?CorrespondanceType
    {
        return $this->type;
    }

    public function setType(?CorrespondanceType $type): void
    {
        $this->type = $type;
    }

    public function getHisto(): ?DateTime
    {
        return $this->histo;
    }

    public function setHisto($histo): void
    {
        $this->histo = $histo;
    }

    /**
     * @return AgentGrade[]
     */
    public function getAgentGrades(): array
    {
        return $this->agentGrades->toArray();
    }

    public function __toString(): string
    {
        return $this->getLibelleCourt();
    }

    public function generateTooltip(): string
    {
        $text = "Libelle court : <strong>" . $this->getLibelleCourt() . "</strong>";
        $text .= "<br/>";
        $text .= "Libelle long : <strong>" . $this->getLibelleLong() . "</strong>";
        return $text;
    }

    public function isHisto(?DateTime $date = null): bool
    {
        if ($date === null) $date = new DateTime();
        return ($this->histo !== null and $date < $this->histo);
    }

}