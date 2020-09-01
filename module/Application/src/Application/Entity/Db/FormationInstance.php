<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class FormationInstance implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var Formation */
    private $formation;

    /** @var ArrayCollection (FormationInstanceJournee) */
    private $journees;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Formation
     */
    public function getFormation()
    {
        return $this->formation;
    }

    /**
     * @param Formation $formation
     * @return FormationInstance
     */
    public function setFormation($formation)
    {
        $this->formation = $formation;
        return $this;
    }

    public function getJournees()
    {
        return $this->journees->toArray();
    }
}