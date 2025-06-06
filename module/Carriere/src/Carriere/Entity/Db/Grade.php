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

class Grade implements HasPeriodeInterface, IsSynchronisableInterface
{
    use IsSynchronisableTrait;
    use HasPeriodeTrait;

    private ?int $id = null;
    private ?string $libelleCourt = null;
    private ?string $libelleLong = null;
    private ?string $code = null;
    private ?DateTime $histo = null;
    private Collection $agentGrades;

    public function __construct()
    {
        $this->agentGrades = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleCourt(): ?string
    {
        return $this->libelleCourt;
    }

    public function getLibelleLong(): ?string
    {
        return $this->libelleLong;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getHisto(): ?DateTime
    {
        return $this->histo;
    }

    public function setHisto(?DateTime $histo): void
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
        $text .= "<br/>";
        $text .= "Code : <strong>" . $this->getCode() . "</strong>";
        return $text;
    }
}