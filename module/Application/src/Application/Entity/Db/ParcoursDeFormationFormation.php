<?php

namespace Application\Entity\Db;

use Formation\Entity\Db\Formation;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class ParcoursDeFormationFormation implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    const DEFAULT_POSITION = 9999;

    /** @var int */
    private $id;
    /** @var ParcoursDeFormation */
    private $parcours;
    /** @var Formation */
    private $formation;
    /** @var int */
    private $ordre;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return ParcoursDeFormation|null
     */
    public function getParcours(): ?ParcoursDeFormation
    {
        return $this->parcours;
    }

    /**
     * @param ParcoursDeFormation $parcours
     * @return ParcoursDeFormationFormation
     */
    public function setParcours(ParcoursDeFormation $parcours): ParcoursDeFormationFormation
    {
        $this->parcours = $parcours;
        return $this;
    }

    /**
     * @return Formation|null
     */
    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    /**
     * @param Formation $formation
     * @return ParcoursDeFormationFormation
     */
    public function setFormation(Formation $formation): ParcoursDeFormationFormation
    {
        $this->formation = $formation;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    /**
     * @param int $ordre
     * @return ParcoursDeFormationFormation
     */
    public function setOrdre(int $ordre): ParcoursDeFormationFormation
    {
        $this->ordre = $ordre;
        return $this;
    }


}