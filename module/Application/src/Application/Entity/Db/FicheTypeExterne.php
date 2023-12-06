<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FicheMetier\Entity\Db\FicheMetier;

/**
 * Class FicheTypeExterne
 * Lien entre une fiche métier et les fiches types
 *
 * NB : $activites stocke la liste des activités conservées dans un string donts les ids sont concaténés avec ;
 */

class FicheTypeExterne {

    const ACTIVITE_SEPARATOR = ";";


    private ?int $id = null;
    private ?FichePoste $fichePoste = null;
    private ?FicheMetier $ficheType = null;
    private ?int $quotite = null;
    private bool $estPrincipale = false;
    private ?string $activites = null;
    private Collection $domaineRepartitions;

    public function __construct()
    {
        $this->domaineRepartitions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFichePoste(): ?FichePoste
    {
        return $this->fichePoste;
    }

    public function setFichePoste(FichePoste $fichePoste): void
    {
        $this->fichePoste = $fichePoste;
    }

    public function getFicheType(): ?FicheMetier
    {
        return $this->ficheType;
    }

    public function setFicheType(FicheMetier $ficheType): void
    {
        $this->ficheType = $ficheType;
    }

    public function getQuotite(): ?int
    {
        return $this->quotite;
    }

    public function setQuotite(int $quotite): void
    {
        $this->quotite = $quotite;
    }

    public function getPrincipale(): bool
    {
        return $this->estPrincipale;
    }

    public function setPrincipale(bool $estPrincipale): void
    {
        $this->estPrincipale = $estPrincipale;
    }

    public function getActivites(): ?string
    {
        return $this->activites;
    }

    public function setActivites(?string $activites): void
    {
        $this->activites = $activites;
    }

    public function getDomaineRepartitions(): Collection
    {
        return $this->domaineRepartitions;
    }

    public function getDomaineRepartitionsAsArray(): array
    {
        $array = [];
        /** @var DomaineRepartition $repartition */
        foreach($this->domaineRepartitions as $repartition) {
            $array[$repartition->getDomaine()->getId()] = $repartition->getQuotite();
        }
        return $array;
    }

    /**
     * @return FicheTypeExterne
     */
    public function clone_it(): FicheTypeExterne
    {
        $result = new FicheTypeExterne();
        $result->setFicheType($this->getFicheType());
        $result->setQuotite($this->getQuotite());
        $result->setPrincipale($this->getPrincipale());
        $result->setActivites($this->getActivites());
        //todo clone la repartition
        return $result;
    }

}