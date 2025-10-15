<?php

namespace Element\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class CompetenceType implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    const CODE_CONNAISSANCE    = 3;
    const CODE_OPERATIONNELLE  = 2;
    const CODE_COMPORTEMENTALE = 1;
    const CODE_SPECIFIQUE = 5;

    private ?int $id = null;
    private ?string $libelle = null;
    private Collection $competences;
    private ?int $ordre = null;

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

    /**
     * @return int
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * @param int $ordre
     * @return CompetenceType
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
        return $this;
    }


}