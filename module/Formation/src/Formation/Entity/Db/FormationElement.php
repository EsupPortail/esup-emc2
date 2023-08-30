<?php

namespace Formation\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use UnicaenValidation\Entity\HasValidationsInterface;
use UnicaenValidation\Entity\HasValidationsTrait;

class FormationElement implements HistoriqueAwareInterface, HasValidationsInterface
{
    use HistoriqueAwareTrait;
    use HasValidationsTrait;

    private ?int $id = null;
    private ?Formation $formation = null;
    private ?string $commentaire = null;

    public function __construct() {
        $this->validations = new ArrayCollection();
    }


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