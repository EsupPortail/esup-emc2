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
    /** @var ArrayCollection (FormationInstanceInscrit) */
    private $inscrits;

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
        if ($this->journees === null) return null;
        return $this->journees->toArray();
    }

    public function getInscrits()
    {
        if ($this->inscrits === null) return null;
        return $this->inscrits->toArray();
    }

    public function getDebut()
    {
        $minimum = null;
        foreach ($this->journees as $journee) {
            $split = explode("/",$journee->getJour());
            $reversed = $split[2]."/".$split[1]."/".$split[0];
            if ($minimum === null OR  $reversed < $minimum) $minimum = $reversed;
        }
        if ($minimum !== null) {
            $split = explode("/",$minimum);
            $minimum = $split[2]."/".$split[1]."/".$split[0];
        }
        return $minimum;
    }

    public function getFin()
    {
        $maximum = null;
        foreach ($this->journees as $journee) {
            $split = explode("/",$journee->getJour());
            $reversed = $split[2]."/".$split[1]."/".$split[0];
            if ($maximum === null OR  $reversed > $maximum) $maximum = $reversed;
        }
        if ($maximum !== null) {
            $split = explode("/",$maximum);
            $maximum = $split[2]."/".$split[1]."/".$split[0];
        }
        return $maximum;
    }
}