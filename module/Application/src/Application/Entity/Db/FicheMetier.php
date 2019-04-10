<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class FicheMetier
{
    use HistoriqueAwareTrait;

    /** @var int */
    private $id;
    /** @var string */
    private $libelle;
    /** @var Agent */
    private $agent;

    /** @var SpecificitePoste */
    private $specificite;
    /** @var FicheMetierType */
    private $metierType;
    /** @var Poste */
    private $poste;

    /** @var ArrayCollection */
    private $fichesTypes;

    public function __invoke()
    {
        $this->fichesTypes = new ArrayCollection();
    }

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
     * @return FicheMetier
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return Agent
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param Agent $agent
     * @return FicheMetier
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return SpecificitePoste
     */
    public function getSpecificite()
    {
        return $this->specificite;
    }

    /**
     * @param SpecificitePoste $specificite
     * @return FicheMetier
     */
    public function setSpecificite($specificite)
    {
        $this->specificite = $specificite;
        return $this;
    }

    /**
     * @return FicheMetierType
     */
    public function getMetierType()
    {
        return $this->metierType;
    }

    /**
     * @param FicheMetierType $metierType
     * @return FicheMetier
     */
    public function setMetierType($metierType)
    {
        $this->metierType = $metierType;
        return $this;
    }

    /**
     * @return Poste
     */
    public function getPoste()
    {
        return $this->poste;
    }

    /**
     * @param Poste $poste
     * @return FicheMetier
     */
    public function setPoste($poste)
    {
        $this->poste = $poste;
        return $this;
    }

    /**
     * @return FicheTypeExterne[]
     */
    public function getFichesTypes()
    {
        return $this->fichesTypes->toArray();
    }

    /**
     * @var FicheTypeExterne $type
     * @return FicheMetier
     */
    public function addFicheTypeExterne($type)
    {
        $this->fichesTypes->add($type);
        return $this;
    }

    /**
     * @var FicheTypeExterne $type
     * @return FicheMetier
     */
    public function removeFicheTypeExterne($type)
    {
        $this->fichesTypes->removeElement($type);
        return $this;
    }



}