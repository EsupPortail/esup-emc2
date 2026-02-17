<?php

namespace Application\Entity\Db;

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
    // Liste des identifiants des missions conservées pour la fiche de poste
    private ?string $missions = null;

    public function __construct()
    {
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

    public function getMissions(): ?string
    {
        return $this->missions;
    }

    public function setMissions(?string $missions): void
    {
        $this->missions = $missions;
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
        $result->setMissions($this->getMissions());
        //todo clone la repartition
        return $result;
    }

}