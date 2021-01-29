<?php

namespace Formation\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;
use UnicaenValidation\Entity\ValidableAwareTrait;
use UnicaenValidation\Entity\ValidableInterface;

class FormationElement implements HistoriqueAwareInterface, ValidableInterface {
    use HistoriqueAwareTrait;
    use ValidableAwareTrait;

    /** @var integer */
    private $id;
    /** @var Formation */
    private $formation;
    /** @var string */
    private $commentaire;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Formation|null
     */
    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    /**
     * @param Formation|null $formation
     * @return FormationElement
     */
    public function setFormation(?Formation $formation): FormationElement
    {
        $this->formation = $formation;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    /**
     * @param string|null $commentaire
     * @return FormationElement
     */
    public function setCommentaire(?string $commentaire): FormationElement
    {
        $this->commentaire = $commentaire;
        return $this;
    }

}