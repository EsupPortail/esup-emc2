<?php

namespace Observation\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class ObservationType implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?string $code = null;
    private ?string $libelle = null;
    private ?string $categorie = null;

    private Collection $observations;

    public function __construct()
    {
        $this->observations = new ArrayCollection();
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

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): void
    {
        $this->categorie = $categorie;
    }

    /** @return Observation[] */
    public function getObservations(): array
    {
        return $this->observations->toArray();
    }

}
