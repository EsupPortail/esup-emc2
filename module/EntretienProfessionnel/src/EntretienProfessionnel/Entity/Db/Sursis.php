<?php

namespace EntretienProfessionnel\Entity\Db;

use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use DateTime;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Sursis implements HistoriqueAwareInterface, HasDescriptionInterface {
    use HistoriqueAwareTrait;
    use HasDescriptionTrait;

    /** @var integer */
    private $id;
    /** @var EntretienProfessionnel */
    private $entretien;
    /** @var DateTime */
    private $sursis;

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

}