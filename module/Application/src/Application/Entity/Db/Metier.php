<?php

namespace Application\Entity\Db;

class Metier {

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var MetierFamille */
    private $famille;
    /** @var Domaine */
    private $domaine;

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
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    /**
     * @return MetierFamille
     */
    public function getFamille()
    {
        return $this->famille;
    }

    /**
     * @param MetierFamille $famille
     * @return Metier
     */
    public function setFamille($famille)
    {
        $this->famille = $famille;
        return $this;
    }

    /**
     * @return Domaine
     */
    public function getDomaine()
    {
        return $this->domaine;
    }

    /**
     * @param Domaine $domaine
     * @return Metier
     */
    public function setDomaine($domaine)
    {
        $this->domaine = $domaine;
        return $this;
    }

    public function __toString()
    {
        return $this->getLibelle();
    }


}