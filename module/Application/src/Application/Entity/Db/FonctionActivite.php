<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FonctionActivite implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $libelle;
    /** @var FonctionDestination */
    private $destination;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return FonctionActivite
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     * @return FonctionActivite
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return FonctionDestination
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param FonctionDestination $destination
     * @return FonctionActivite
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
        return $this;
    }


}