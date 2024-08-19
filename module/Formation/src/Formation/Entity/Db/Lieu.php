<?php

namespace Formation\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Lieu implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?string $libelle = null;
    private ?string $batiment = null;
    private ?string $campus = null;
    private ?string $ville = null;

    private Collection $seances;

    public function __construct() {
        $this->seances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public function getBatiment(): ?string
    {
        return $this->batiment;
    }

    public function setBatiment(?string $batiment): void
    {
        $this->batiment = $batiment;
    }

    public function getCampus(): ?string
    {
        return $this->campus;
    }

    public function setCampus(?string $campus): void
    {
        $this->campus = $campus;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): void
    {
        $this->ville = $ville;
    }

    /** @return Seance[] */
    public function getSeances(bool $withHisto = false): array
    {
        $seances = $this->seances->toArray();
        if (!$withHisto) {
            $seances = array_filter($seances, function (Seance $seance) { return $seance->estNonHistorise();});
        }
        return $seances;
    }



}