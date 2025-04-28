<?php

namespace Metier\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FamilleProfessionnelle implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?string $libelle = null;

    private Collection $metiers;

    public function __construct()
    {
        $this->metiers = new ArrayCollection();
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getLibelle() : ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle) : void
    {
        $this->libelle = $libelle;
    }

    /** @return Metier[] */
    public function getMetiers() : array
    {
        return $this->metiers->toArray();
    }

    public function __toString() : string
    {
        return $this->getLibelle();
    }
}