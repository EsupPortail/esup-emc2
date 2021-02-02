<?php

namespace EntretienProfessionnel\Entity\Db;

use DateTime;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Sursis implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var EntretienProfessionnel */
    private $entretien;
    /** @var DateTime */
    private $sursis;
    /** @var string */
    private $description;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return EntretienProfessionnel|null
     */
    public function getEntretien(): ?EntretienProfessionnel
    {
        return $this->entretien;
    }

    /**
     * @param EntretienProfessionnel|null $entretien
     * @return Sursis
     */
    public function setEntretien(?EntretienProfessionnel $entretien): Sursis
    {
        $this->entretien = $entretien;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getSursis(): ?DateTime
    {
        return $this->sursis;
    }

    /**
     * @param DateTime|null $sursis
     * @return Sursis
     */
    public function setSursis(?DateTime $sursis): Sursis
    {
        $this->sursis = $sursis;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Sursis
     */
    public function setDescription(?string $description): Sursis
    {
        $this->description = $description;
        return $this;
    }



}