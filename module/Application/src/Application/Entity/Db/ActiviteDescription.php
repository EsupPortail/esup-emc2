<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class ActiviteDescription implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var Activite */
    private $activite;
    /** @var string */
    private $description;
    /** @var integer */
    private $ordre;

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
     * @return ActiviteDescription
     */
    public function setActivite($activite)
    {
        $this->activite = $activite;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return ActiviteDescription
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return integer
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * @param integer $ordre
     * @return ActiviteDescription
     */
    public function setOrdre(int $ordre)
    {
        $this->ordre = $ordre;
        return $this;
    }
}

