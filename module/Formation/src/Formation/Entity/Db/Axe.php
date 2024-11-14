<?php

namespace Formation\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenSynchro\Entity\Db\IsSynchronisableInterface;
use UnicaenSynchro\Entity\Db\IsSynchronisableTrait;


class Axe implements IsSynchronisableInterface
{
    use IsSynchronisableTrait;

    private ?int $id = -1;
    private ?string $libelle = null;
    private Collection $formations;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function getFormations(): Collection
    {
        return $this->formations;
    }

}