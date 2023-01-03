<?php

namespace Carriere\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Metier\Entity\Db\Metier;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Categorie implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?string $code = null;
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

    public function getCode() : ?string
    {
        return $this->code;
    }

    public function setCode(string $code) : void
    {
        $this->code = $code;
    }

    public function getLibelle() : ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle) : void
    {
        $this->libelle = $libelle;
    }

    /**
     * @return Metier[]
     */
    public function getMetiers(): array
    {
        return $this->metiers->toArray();
    }

}