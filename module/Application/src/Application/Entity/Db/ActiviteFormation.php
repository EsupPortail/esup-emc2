<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use Formation\Entity\Db\Formation;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class ActiviteFormation implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var Activite */
    private $activite;
    /** @var Formation */
    private $formation;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Activite
     */
    public function getActivite()
    {
        return $this->activite;
    }

    /**
     * @param Activite $activite
     * @return ActiviteFormation
     */
    public function setActivite($activite)
    {
        $this->activite = $activite;
        return $this;
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
     * @return ActiviteFormation
     */
    public function setFormation($formation)
    {
        $this->formation = $formation;
        return $this;
    }

}
