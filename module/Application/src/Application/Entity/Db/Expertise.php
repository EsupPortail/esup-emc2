<?php

namespace Application\Entity\Db;


use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Expertise implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var FichePoste */
    private $ficheposte;
    /** @var string */
    private $libelle;
    /** @var string */
    private $description;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FichePoste
     */
    public function getFicheposte()
    {
        return $this->ficheposte;
    }

    /**
     * @param FichePoste $ficheposte
     * @return Expertise
     */
    public function setFicheposte($ficheposte)
    {
        $this->ficheposte = $ficheposte;
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
     * @return Expertise
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
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
     * @param string $description
     * @return Expertise
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
}
