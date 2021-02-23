<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Application\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Application\Entity\Db\Traits\HasApplicationCollectionTrait;
use Application\Entity\Db\Traits\HasCompetenceCollectionTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Formation\Entity\Db\Formation;
use Metier\Entity\Db\Metier;
use UnicaenEtat\Entity\Db\HasEtatInterface;
use UnicaenEtat\Entity\Db\HasEtatTrait;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class FicheMetier implements HistoriqueAwareInterface, HasEtatInterface,
    HasApplicationCollectionInterface, HasCompetenceCollectionInterface {
    use HistoriqueAwareTrait;
    use HasEtatTrait;
    use HasApplicationCollectionTrait;
    use HasCompetenceCollectionTrait;

    const TYPE_FICHEMETIER = 'FICHE_METIER';
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

    /**
     * @return string
     */
    public function getMissions()
    {
        $texte = "";
        foreach ($this->getActivites() as $activite) {
            $texte .= "<h3 class='mission-principale'>" . $activite->getActivite()->getLibelle() . "</h3>";
            /** @var ActiviteDescription[] $descriptions */
            $descriptions = $activite->getActivite()->getDescriptions();
            $texte .= "<ul>";
            foreach ($descriptions as $description) {
                $texte .= "<li>";
                $texte .= $description->getLibelle();
                $texte .= "</li>";
            }
            $texte .= "</ul>";
        }
        return $texte;
    }

    public function getCompetences() {
        $competences = $this->getCompetenceListe();

        $texte = "";
        $texte .= "<ul>";
        foreach ($competences as $competence) {
            $texte .= "<li>";
            $texte .= $competence->getCompetence()->getLibelle();
            $texte .= "</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }

    /**
     * @param int $typeId
     * @return string
     */
    public function getComptencesByType(int $typeId) : string
    {
        $competences = $this->getCompetenceListe();
        $competences = array_map(function (CompetenceElement $c) { return $c->getCompetence();}, $competences);
        $competences = array_filter($competences, function (Competence $c) use ($typeId) { return $c->getType()->getId() === $typeId;});
        usort($competences, function (Competence $a, Competence $b) { return $a->getLibelle() > $b->getLibelle();});

        $texte = "<ul>";
        foreach ($competences as $competence) {
            $texte .= "<li>";
            $texte .= $competence->getLibelle();
            $texte .= "</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }

    /**
     * @return string
     */
    public function getConnaissances() : string
    {
        return $this->getComptencesByType(CompetenceType::CODE_CONNAISSANCE);
    }

    /**
     * @return string
     */
    public function getCompetencesOperationnelles() : string
    {
        return $this->getComptencesByType(CompetenceType::CODE_OPERATIONNELLE);
    }

    /**
     * @return string
     */
    public function getCompetencesComportementales() : string
    {
        return $this->getComptencesByType(CompetenceType::CODE_COMPORTEMENTALE);
    }

    public function getApplicationsAffichage() {
        $applications = $this->getApplicationListe();

        $texte = "";
        $texte .= "<ul>";
        /** @var ApplicationElement $applicationElement */
        foreach ($applications as $applicationElement) {
            $application = $applicationElement->getApplication();
            $texte .= "<li>".$application->getLibelle()."</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }
}