<?php

namespace Carriere\Entity\Db;

use Application\Entity\Db\AgentGrade;
use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\DbImportableAwareTrait;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class EmploiType implements HasPeriodeInterface
{
    use DbImportableAwareTrait;
    use HasPeriodeTrait;

    private ?int $id = null;
    private ?string $code = null;
    private ?string $libelleCourt = null;
    private ?string $libelleLong = null;

    private Collection $agentGrades;

    public function __construct()
    {
        $this->agentGrades = new ArrayCollection();
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getCode() : ?string
    {
        return $this->code;
    }

    public function setCode(?string $code) : void
    {
        $this->code = $code;
    }

    public function getLibelleCourt() : ?string
    {
        return $this->libelleCourt;
    }

    public function getLibelleLong(): ?string
    {
        return $this->libelleLong;
    }

    /** @return AgentGrade[] */
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