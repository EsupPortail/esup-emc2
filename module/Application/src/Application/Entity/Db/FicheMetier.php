<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenEtat\Entity\Db\HasEtatInterface;
use UnicaenEtat\Entity\Db\HasEtatTrait;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class FicheMetier implements HistoriqueAwareInterface, HasEtatInterface {
    use HistoriqueAwareTrait;
    use HasEtatTrait;

    const ETAT_REDACTION = 'FICHE_METIER_REDACTION';
    const ETAT_VALIDE    = 'FICHE_METIER_OK';
    const ETAT_MASQUE    = 'FICHE_METIER_MASQUEE';

    /** @var int */
    private $id;
    /** @var Metier */
    private $metier;
    /** @var boolean */
    private $hasExpertise;

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
     * @param Metier|null $metier
     * @return FicheMetier
     */
    public function setMetier(?Metier $metier)
    {
        $this->metier = $metier;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasExpertise() {
        return $this->hasExpertise;
    }

    /**
     * @param bool $has
     * @return $this
     */
    public function setExpertise(bool $has) {
        $this->hasExpertise = $has;
        return $this;
    }

     /**
     * @return string
     */
    public function getMissionsPrincipales()
    {
        $texte = '<ul>';
        $activites = $this->getActivites();
        usort($activites, function (FicheMetierTypeActivite $a, FicheMetierTypeActivite $b) {return $a->getPosition() > $b->getPosition();});
        foreach ($activites as $activite) {
            $texte .= '<li>'.$activite->getActivite()->getLibelle().'</li>';
        }
        $texte .= '</ul>';
        return $texte;
    }

    /** ACTIVITE ******************************************************************************************************/

    /**
     * Pour simplifier le tri selon la position est fait à ce niveau
     * @return FicheMetierTypeActivite[]
     */
    public function getActivites()
    {
        $activites =  $this->activites->toArray();
        usort($activites, function (FicheMetierTypeActivite $a, FicheMetierTypeActivite $b) { return $a->getPosition() > $b->getPosition();});
        return $activites;
    }

    /**
     * @param FicheMetierTypeActivite $activite
     * @return FicheMetier
     */
    public function addActivite(FicheMetierTypeActivite $activite)
    {
        $this->activites->add($activite);
        return $this;
    }

    /**
     * @param FicheMetierTypeActivite $activite
     * @return FicheMetier
     */
    public function removeActivite(FicheMetierTypeActivite $activite)
    {
        $this->activites->removeElement($activite);
        return $this;
    }

    /** APPLICATION ***************************************************************************************************/

    /**
     * @return Application[]
     */
    public function getApplications()
    {
        return $this->applications->toArray();
    }

    /**
     * @param Application $application
     * @return bool
     */
    public function hadApplication(Application $application)
    {
        return $this->applications->contains($application);
    }

    /**
     * @param Application $application
     * @return FicheMetier
     */
    public function addApplication(Application $application)
    {
        $this->applications->add($application);
        return $this;
    }

    /**
     * @param Application $application
     * @return FicheMetier
     */
    public function removeApplication(Application $application)
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
     * @return Competence[]
     */
    public function getCompetences()
    {
        return $this->competences->toArray();
    }

    /**
     * @param Competence $competence
     * @return FicheMetier
     */
    public function addCompetence(Competence $competence)
    {
        $this->competences->add($competence);
        return $this;
    }

    /**
     * @param Competence $competence
     * @return FicheMetier
     */
    public function removeCompetence(Competence $competence)
    {
        $this->competences->removeElement($competence);
        return $this;
    }

    /**
     * @param Competence $competence
     * @return boolean
     */
    public function hasCompetence(Competence $competence)
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
    public function addFormation(Formation $formation)
    {
        $this->formations->add($formation);
        return $this;
    }

    /**
     * @param Formation $formation
     * @return FicheMetier
     */
    public function removeFormation(Formation $formation)
    {
        $this->formations->removeElement($formation);
        return $this;
    }

    /**
     * @param Formation $formation
     * @return boolean
     */
    public function hasFormation(Formation $formation)
    {
        return $this->formations->contains($formation);
    }

    public function clearFormations()
    {
        $this->formations->clear();
    }

    /** FONCTION POUR MACRO *******************************************************************************************/

    /**
     * @return string
     */
    public function getIntitule() {
        $metier = $this->getMetier();
        if ($metier === null) return "Aucun métier est associé.";
        return $metier->getLibelle();
    }

}