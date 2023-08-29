<?php

namespace Formation\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use UnicaenValidation\Entity\ValidableAwareTrait;
use UnicaenValidation\Entity\ValidableInterface;

class FormationElement implements HistoriqueAwareInterface, ValidableInterface
{
    use HistoriqueAwareTrait;
    use ValidableAwareTrait;

    private ?int $id = null;
    private ?Formation $formation = null;
    private ?string $commentaire = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): FormationElement
    {
        $this->formation = $formation;
        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): FormationElement
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function getObjet(): ?Formation
    {
        return $this->getFormation();
    }

    public function getLibelle(): ?string
    {
        return $this->getFormation()->getLibelle();
    }
}