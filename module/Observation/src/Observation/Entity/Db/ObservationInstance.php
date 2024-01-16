<?php

namespace Observation\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use UnicaenValidation\Entity\HasValidationsInterface;
use UnicaenValidation\Entity\HasValidationsTrait;

class ObservationInstance implements HistoriqueAwareInterface, HasValidationsInterface
{
    use HistoriqueAwareTrait;
    use HasValidationsTrait;

    private ?int $id = null;
    private ?ObservationType $type = null;
    private ?string $observation = null;

    public function __construct()
    {
        $this->validations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?ObservationType
    {
        return $this->type;
    }

    public function setType(?ObservationType $type): void
    {
        $this->type = $type;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(?string $observation): void
    {
        $this->observation = $observation;
    }

}
