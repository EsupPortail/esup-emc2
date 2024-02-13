<?php

namespace Metier\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Domaine implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?string $libelle = null;
    private ?string $typeFonction = null;

    private Collection $familles;
    private Collection $metiers;

    public function __construct()
    {
        $this->metiers = new ArrayCollection();
        $this->familles = new ArrayCollection();
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

    public function getTypeFonction() : ?string
    {
        return $this->typeFonction;
    }

    public function setTypeFonction(?string $typeFonction) : void
    {
        $this->typeFonction = $typeFonction;
    }

    /**
     * @return FamilleProfessionnelle[]
     */
    public function getFamilles() : array
    {
        $familles =  $this->familles->toArray();
        usort($familles, function (FamilleProfessionnelle $a, FamilleProfessionnelle $b) { return $a->getLibelle() <=> $b->getLibelle();});
        return $familles;
    }


    public function clearFamilles() : void
    {
        $this->familles->clear();
    }

    public function addFamille(FamilleProfessionnelle $famille) : void
    {
        $this->familles->add($famille);
    }

    /**
     * @return Metier[]
     */
    public function getMetiers() : array
    {
        return $this->metiers->toArray();
    }

    public function __toString() : string
    {
        return $this->getLibelle();
    }
}