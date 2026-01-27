<?php

namespace Application\Entity\Db;

use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\Mission;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FicheposteActiviteDescriptionRetiree implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    private ?int $id;
    private ?FichePoste $fichePoste = null;
    private ?FicheMetier $ficheMetier = null;
    private ?Mission $mission = null;

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

    public function getFicheMetier(): ?FicheMetier
    {
        return $this->ficheMetier;
    }

    public function setFicheMetier(?FicheMetier $ficheMetier): void
    {
        $this->ficheMetier = $ficheMetier;
    }

    public function getMission(): Mission
    {
        return $this->mission;
    }

    public function setMission(?Mission $mission): void
    {
        $this->mission = $mission;
    }


}