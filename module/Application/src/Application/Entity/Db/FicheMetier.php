<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class FicheMetier {
    use HistoriqueAwareTrait;

    /** @var int */
    private $id;
    /** @var Metier */
    private $metier;

    /** @var string */
    private $connaissances;
    /** @var string */
    private $competencesOperationnelles;
    /** @var string */
    private $competencesComportementales;

    /** @var ArrayCollection */
    private $activites;
    /** @var ArrayCollection */
    private $applications;
    /** @var ArrayCollection */
    private $competences;
    /** @var ArrayCollection */
    private $formations;



    public function __construct()
    {
        $this->activites = new ArrayCollection();
        $this->applications = new ArrayCollection();
        $this->competences = new ArrayCollection();
        $this->formations = new ArrayCollection();
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Metier
     */
    public function getMetier()
    {
        return $this->metier;
    }

    /**
     * @param Metier $metier
     * @return FicheMetier
     */
    public function setMetier($metier)
    {
        $this->metier = $metier;
        return $this;
    }

    /**
     * @return string
     */
    public function getMissionsPrincipales()
    {
        $texte = '<ul>';
        //return $this->missionsPrincipales;
        $activites = $this->getActivites();
        usort($activites, function (FicheMetierTypeActivite $a, FicheMetierTypeActivite $b) {return $a->getPosition() > $b->getPosition();});
        foreach ($activites as $activite) {
            $texte .= '<li>'.$activite->getActivite()->getLibelle().'</li>';
        }
        $texte .= '</ul>';
        return $texte;
    }

    /**
     * @return string
     */
    public function getConnaissances()
    {
        return $this->connaissances;
    }

    /**
     * @param string $connaissances
     * @return FicheMetier
     */
    public function setConnaissances($connaissances)
    {
        $this->connaissances = $connaissances;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompetencesOperationnelles()
    {
        return $this->competencesOperationnelles;
    }

    /**
     * @param string $competencesOperationnelles
     * @return FicheMetier
     */
    public function setCompetencesOperationnelles($competencesOperationnelles)
    {
        $this->competencesOperationnelles = $competencesOperationnelles;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompetencesComportementales()
    {
        return $this->competencesComportementales;
    }

    /**
     * @param string $competencesComportementales
     * @return FicheMetier
     */
    public function setCompetencesComportementales($competencesComportementales)
    {
        $this->competencesComportementales = $competencesComportementales;
        return $this;
    }

    /** ACTIVITE ******************************************************************************************************/

    /**
     * @return FicheMetierTypeActivite[]
     */
    public function getActivites()
    {
        return $this->activites->toArray();
    }

    /**
     * @param FicheMetierTypeActivite $activite
     * @return FicheMetier
     */
    public function addActivite($activite)
    {
        $this->activites->add($activite);
        return $this;
    }

    /**
     * @param FicheMetierTypeActivite $activite
     * @return FicheMetier
     */
    public function removeActivite($activite)
    {
        $this->activites->removeElement($activite);
        return $this;
    }

    /** APPLICATION ***************************************************************************************************/

    /**
     * @return ArrayCollection
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * @param Application $application
     * @return bool
     */
    public function hadApplication($application)
    {
        return $this->applications->contains($application);
    }

    /**
     * @param Application $application
     * @return FicheMetier
     */
    public function addApplication($application)
    {
        $this->applications->add($application);
        return $this;
    }

    /**
     * @param Application $application
     * @return FicheMetier
     */
    public function removeApplication($application)
    {
        $this->applications->removeElement($application);
        return $this;
    }

    /**
     * @return FicheMetier
     */
    public function clearApplications()
    {
        $this->applications->clear();
        return $this;
    }
    /** COMPETENCE ****************************************************************************************************/

    /**
     * @return ArrayCollection
     */
    public function getCompetences()
    {
        return $this->competences;
    }

    /**
     * @param Competence $competence
     * @return FicheMetier
     */
    public function addCompetence($competence)
    {
        $this->competences->add($competence);
        return $this;
    }

    /**
     * @param Competence $competence
     * @return FicheMetier
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
     * @return FicheMetier
     */
    public function clearCompetences()
    {
        $this->competences->clear();
        return $this;
    }

    /** FORMATION *****************************************************************************************************/

    /**
     * @return Formation[]
     */
    public function getFormations()
    {
        return $this->formations->toArray();
    }

    /**
     * @param Formation $formation
     * @return FicheMetier
     */
    public function addFormation($formation)
    {
        $this->formations->add($formation);
        return $this;
    }

    /**
     * @param Formation $formation
     * @return FicheMetier
     */
    public function removeFormation($formation)
    {
        $this->formations->removeElement($formation);
        return $this;
    }

    /**
     * @param Formation $formation
     * @return boolean
     */
    public function hasFormation($formation)
    {
        return $this->formations->contains($formation);
    }

    public function clearFormations()
    {
        $this->formations->clear();
    }


}