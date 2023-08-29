<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Entity\Db\Traits\HasSourceTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FormationInstanceFrais implements HistoriqueAwareInterface, HasSourceInterface
{
    use HistoriqueAwareTrait;
    use HasSourceTrait;

    private ?int $id = null;
    private ?FormationInstanceInscrit $inscrit = null;
    private ?float $fraisRepas = null;
    private ?float $fraisHebergement = null;
    private ?float  $fraisTransport = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getInscrit(): ?FormationInstanceInscrit
    {
        return $this->inscrit;
    }

    public function setInscrit(?FormationInstanceInscrit $inscrit): void
    {
        $this->inscrit = $inscrit;
    }

    public function getFraisRepas(): ?float
    {
        return $this->fraisRepas;
    }

    public function setFraisRepas(float $fraisRepas): void
    {
        $this->fraisRepas = $fraisRepas;
    }

    public function getFraisHebergement(): ?float
    {
        return $this->fraisHebergement;
    }

    public function setFraisHebergement(float $fraisHebergement): void
    {
        $this->fraisHebergement = $fraisHebergement;
    }

    public function getFraisTransport(): ?float
    {
        return $this->fraisTransport;
    }

    public function setFraisTransport(float $fraisTransport): void
    {
        $this->fraisTransport = $fraisTransport;
    }

    public function afficheFrais(): string
    {
        $text = "<strong> Repas </strong> : " . (($this->getFraisRepas()) ?: "0.00") . " € <br/>";
        $text .= "<strong> Hébergement </strong> : " . (($this->getFraisHebergement()) ?: "0.00") . " € <br/>";
        $text .= "<strong> Transport </strong> : " . (($this->getFraisTransport()) ?: "0.00") . " € ";
        return $text;
    }

}