<?php

namespace Application\Entity\Db;

class Poste {

    /** @var integer */
    private $id;
    /** @var string */
    private $numeroPoste;
    /** @var Affectation */
    private $affectation;
    /** @var string */
    private $localisation;
    /** @var Correspondance */
    private $correspondance;
    /** @var Agent */
    private $rattachementHierarchique;
    /** @var Domaine */
    private $domaine;
    /** @var string */
    private $lien;

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
    public function getNumeroPoste()
    {
        return $this->numeroPoste;
    }

    /**
     * @param string $numeroPoste
     * @return Poste
     */
    public function setNumeroPoste($numeroPoste)
    {
        $this->numeroPoste = $numeroPoste;
        return $this;
    }

    /**
     * @return Affectation
     */
    public function getAffectation()
    {
        return $this->affectation;
    }

    /**
     * @param Affectation $affectation
     * @return Poste
     */
    public function setAffectation($affectation)
    {
        $this->affectation = $affectation;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * @param string $localisation
     * @return Poste
     */
    public function setLocalisation($localisation)
    {
        $this->localisation = $localisation;
        return $this;
    }

    /**
     * @return Correspondance
     */
    public function getCorrespondance()
    {
        return $this->correspondance;
    }

    /**
     * @param Correspondance $correspondance
     * @return Poste
     */
    public function setCorrespondance($correspondance)
    {
        $this->correspondance = $correspondance;
        return $this;
    }

    /**
     * @return Agent
     */
    public function getRattachementHierarchique()
    {
        return $this->rattachementHierarchique;
    }

    /**
     * @param Agent $rattachementHierarchique
     * @return Poste
     */
    public function setRattachementHierarchique($rattachementHierarchique)
    {
        $this->rattachementHierarchique = $rattachementHierarchique;
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
     * @return Poste
     */
    public function setDomaine($domaine)
    {
        $this->domaine = $domaine;
        return $this;
    }

    /**
     * @return string
     */
    public function getLien()
    {
        return $this->lien;
    }

    /**
     * @param string $lien
     * @return Poste
     */
    public function setLien($lien)
    {
        $this->lien = $lien;
        return $this;
    }
}