<?php

namespace Formation\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class ActionCoutPrevisionnel implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?Formation $action = null;
    private ?PlanDeFormation $plan = null;
    private ?float $coutParSession = null;
    private ?int $nombreDeSession = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAction(): ?Formation
    {
        return $this->action;
    }

    public function setAction(?Formation $action): void
    {
        $this->action = $action;
    }

    public function getPlan(): ?PlanDeFormation
    {
        return $this->plan;
    }

    public function setPlan(?PlanDeFormation $plan): void
    {
        $this->plan = $plan;
    }

    public function getCoutParSession(): ?float
    {
        return $this->coutParSession;
    }

    public function setCoutParSession(?float $coutParSession): void
    {
        $this->coutParSession = $coutParSession;
    }

    public function getNombreDeSession(): ?int
    {
        return $this->nombreDeSession;
    }

    public function setNombreDeSession(?int $nombreDeSession): void
    {
        $this->nombreDeSession = $nombreDeSession;
    }

}