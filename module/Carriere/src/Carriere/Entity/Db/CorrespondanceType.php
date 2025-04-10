<?php

namespace Carriere\Entity\Db;

use Application\Entity\Db\Traits\HasDescriptionTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenSynchro\Entity\Db\IsSynchronisableInterface;
use UnicaenSynchro\Entity\Db\IsSynchronisableTrait;

class CorrespondanceType implements IsSynchronisableInterface
{
    use IsSynchronisableTrait;
    use HasDescriptionTrait;

    private ?int $id = null;
    private ?string $code = null;
    private ?string $libelleCourt = null;
    private ?string $libelleLong = null;
    private ?DateTime $dateOuverture = null;
    private ?DateTime $dateFermeture = null;

    private Collection $correspondances;

    public function __construct()
    {
        $this->correspondances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getLibelleCourt(): ?string
    {
        return $this->libelleCourt;
    }

    public function getLibelleLong(): ?string
    {
        return $this->libelleLong;
    }

    public function getDateOuverture(): ?DateTime
    {
        return $this->dateOuverture;
    }

    public function getDateFermeture(): ?DateTime
    {
        return $this->dateFermeture;
    }

    public function getCorrespondances(): Collection
    {
        return $this->correspondances;
    }


}