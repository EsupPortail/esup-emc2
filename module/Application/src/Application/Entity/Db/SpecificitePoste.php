<?php

namespace Application\Entity\Db;

class SpecificitePoste {

    /** @var integer */
    private $id;
    /** @var FichePoste */
    private $fiche;

    /** @var string */
    private $specificite;
    /** @var string */
    private $encadrement;
    /** @var string */
    private $relationsInternes;
    /** @var string */
    private $relationsExternes;
    /** @var string */
    private $contraintes;
    /** @var string */
    private $moyens;
    /** @var string */
    private $formations;

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
    public function getFiche()
    {
        return $this->fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return SpecificitePoste
     */
    public function setFiche($fiche)
    {
        $this->fiche = $fiche;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpecificite()
    {
        return $this->specificite;
    }

    /**
     * @param string $specificite
     * @return SpecificitePoste
     */
    public function setSpecificite($specificite)
    {
        $this->specificite = $specificite;
        return $this;
    }

    /**
     * @return string
     */
    public function getEncadrement()
    {
        return $this->encadrement;
    }

    /**
     * @param string $encadrement
     * @return SpecificitePoste
     */
    public function setEncadrement($encadrement)
    {
        $this->encadrement = $encadrement;
        return $this;
    }

    /**
     * @return string
     */
    public function getRelationsInternes()
    {
        return $this->relationsInternes;
    }

    /**
     * @param string $relationsInternes
     * @return SpecificitePoste
     */
    public function setRelationsInternes($relationsInternes)
    {
        $this->relationsInternes = $relationsInternes;
        return $this;
    }

    /**
     * @return string
     */
    public function getRelationsExternes()
    {
        return $this->relationsExternes;
    }

    /**
     * @param string $relationsExternes
     * @return SpecificitePoste
     */
    public function setRelationsExternes($relationsExternes)
    {
        $this->relationsExternes = $relationsExternes;
        return $this;
    }

    /**
     * @return string
     */
    public function getContraintes()
    {
        return $this->contraintes;
    }

    /**
     * @param string $contraintes
     * @return SpecificitePoste
     */
    public function setContraintes($contraintes)
    {
        $this->contraintes = $contraintes;
        return $this;
    }

    /**
     * @return string
     */
    public function getMoyens()
    {
        return $this->moyens;
    }

    /**
     * @param string $moyens
     * @return SpecificitePoste
     */
    public function setMoyens($moyens)
    {
        $this->moyens = $moyens;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormations()
    {
        return $this->formations;
    }

    /**
     * @param string $formations
     * @return SpecificitePoste
     */
    public function setFormations($formations)
    {
        $this->formations = $formations;
        return $this;
    }


    /** @return SpecificitePoste */
    public function clone_it()
    {
        $result = new SpecificitePoste();
        $result->setSpecificite($this->getSpecificite());
        $result->setEncadrement($this->getEncadrement());
        $result->setRelationsInternes($this->getRelationsInternes());
        $result->setRelationsExternes($this->getRelationsExternes());
        $result->setContraintes($this->getContraintes());
        $result->setMoyens($this->getMoyens());
        $result->setFormations($this->getFormations());
        return $result;
    }


}