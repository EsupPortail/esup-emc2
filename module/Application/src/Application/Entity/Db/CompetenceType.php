<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class CompetenceType {
    use HistoriqueAwareTrait;

    const CODE_CONNAISSANCE    = 3;
    const CODE_OPERATIONNELLE  = 2;
    const CODE_COMPORTEMENTALE = 1;

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var ArrayCollection (Competence) */
    private $competences;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
    }

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
     * @return CompetenceType
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }


    /**
     * @return Competence[]
     */
    public function getCompetences()
    {
        return $this->competences->toArray();
    }

    /**
     * @param Competence $competence
     * @return CompetenceType
     */
    public function addCompetence($competence)
    {
        $this->competences->add($competence);
        return $this;
    }

    /**
     * @param Competence $competence
     * @return CompetenceType
     */
    public function removeCompetence($competence)
    {
        $this->competences->removeElement($competence);
        return $this;
    }

    /**
     * @param Competence $competence
     * @return boolean
     */
    public function hasCompetence($competence)
    {
        return $this->competences->contains($competence);
    }
}