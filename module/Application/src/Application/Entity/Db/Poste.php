<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Poste implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = -1;
    private ?FichePoste $ficheposte = null;
    private ?string $referentiel = null;
    private ?string $intitule = null;
    private ?string $posteId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFicheposte(): ?FichePoste
    {
        return $this->ficheposte;
    }

    public function setFicheposte(?FichePoste $ficheposte): void
    {
        $this->ficheposte = $ficheposte;
    }

    public function getReferentiel(): ?string
    {
        return $this->referentiel;
    }

    public function setReferentiel(?string $referentiel): void
    {
        $this->referentiel = $referentiel;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(?string $intitule): void
    {
        $this->intitule = $intitule;
    }

    public function getPosteId(): ?string
    {
        return $this->posteId;
    }

    public function setPosteId(?string $posteId): void
    {
        $this->posteId = $posteId;
    }

}