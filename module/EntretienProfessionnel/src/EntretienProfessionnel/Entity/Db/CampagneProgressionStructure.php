<?php

namespace EntretienProfessionnel\Entity\Db;


use DateTime;
use Structure\Entity\Db\Structure;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class CampagneProgressionStructure implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    private ?int $id;
    private ?Campagne $campagne = null;
    private ?Structure $structure = null;
    private int $nbTotal = 0;
    private int $nbComplet = 0;
    private int $nbAutorite = 0;
    private int $nbObservation = 0;
    private int $nbSuperieur = 0;
    private int $nbPlanifier = 0;
    private int $nbManquant = 0;
    private ?DateTime $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCampagne(): ?Campagne
    {
        return $this->campagne;
    }

    public function setCampagne(?Campagne $campagne): void
    {
        $this->campagne = $campagne;
    }

    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    public function setStructure(?Structure $structure): void
    {
        $this->structure = $structure;
    }

    public function getNbTotal(): int
    {
        return $this->nbTotal;
    }

    public function setNbTotal(int $nbTotal): void
    {
        $this->nbTotal = $nbTotal;
    }

    public function getNbComplet(): int
    {
        return $this->nbComplet;
    }

    public function setNbComplet(int $nbComplet): void
    {
        $this->nbComplet = $nbComplet;
    }

    public function getNbAutorite(): int
    {
        return $this->nbAutorite;
    }

    public function setNbAutorite(int $nbAutorite): void
    {
        $this->nbAutorite = $nbAutorite;
    }

    public function getNbObservation(): int
    {
        return $this->nbObservation;
    }

    public function setNbObservation(int $nbObservation): void
    {
        $this->nbObservation = $nbObservation;
    }

    public function getNbSuperieur(): int
    {
        return $this->nbSuperieur;
    }

    public function setNbSuperieur(int $nbSuperieur): void
    {
        $this->nbSuperieur = $nbSuperieur;
    }

    public function getNbPlanifier(): int
    {
        return $this->nbPlanifier;
    }

    public function setNbPlanifier(int $nbPlanifier): void
    {
        $this->nbPlanifier = $nbPlanifier;
    }

    public function getNbManquant(): int
    {
        return $this->nbManquant;
    }

    public function setNbManquant(int $nbManquant): void
    {
        $this->nbManquant = $nbManquant;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(?DateTime $date): void
    {
        $this->date = $date;
    }

    public function getNbEntretiens(): int
    {
        $nb = 0;
        $nb += $this->getNbComplet();
        $nb += $this->getNbAutorite();
        $nb += $this->getNbObservation();
        $nb += $this->getNbSuperieur();
        $nb += $this->getNbPlanifier();

        return $nb;
    }
}

