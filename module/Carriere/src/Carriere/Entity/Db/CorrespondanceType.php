<?php

namespace Carriere\Entity\Db;

use Application\Entity\Db\Traits\DbImportableAwareTrait;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use DateTime;
use Doctrine\Common\Collections\Collection;

class CorrespondanceType  {
    use DbImportableAwareTrait;
    use HasDescriptionTrait;

    private ?int $id = null;
    private ?string $code = null;
    private ?string $libelleCourt = null;
    private ?string $libelleLong = null;
    private ?DateTime $dateOuverture = null;
    private ?DateTime $dateFermeture = null;

    private Collection $correspondances;

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

    public function setLibelleCourt(?string $libelleCourt): void
    {
        $this->libelleCourt = $libelleCourt;
    }

    public function getLibelleLong(): ?string
    {
        return $this->libelleLong;
    }

    public function setLibelleLong(?string $libelleLong): void
    {
        $this->libelleLong = $libelleLong;
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

    public function getCorrespondances(): Collection
    {
        return $this->correspondances;
    }


}