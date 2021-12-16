<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class ActiviteLibelle implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var Activite */
    private $activite;
    /** @var string */
    private $libelle;

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
     * @return ActiviteLibelle
     */
    public function setActivite(Activite $activite)
    {
        $this->activite = $activite;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return ActiviteLibelle
     */
    public function setLibelle(string $libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

}