<?php

namespace Application\Entity\Db;

class Grade {

    /** @var integer */
    private $id;
    /** @var Corps */
    private $corps;
    /** @var string */
    private $libelle;
    /** @var integer */
    private $rang;
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Corps
     */
    public function getCorps()
    {
        return $this->corps;
    }

    /**
     * @param Corps $corps
     * @return Grade
     */
    public function setCorps($corps)
    {
        $this->corps = $corps;
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
     * @return Grade
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    public function __toString()
    {
        $texte = $this->getCorps()->getLibelle() . " ". $this->getLibelle();
        return $texte;
    }

    /**
     * @return int
     */
    public function getRang()
    {
        return $this->rang;
    }

    /**
     * @param int $rang
     * @return Grade
     */
    public function setRang($rang)
    {
        $this->rang = $rang;
        return $this;
    }


}