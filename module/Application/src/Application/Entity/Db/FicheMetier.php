<?php

namespace Application\Entity\Db;

use Element\Entity\Db\ApplicationElement;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use Element\Entity\Db\CompetenceType;
use Element\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Element\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Element\Entity\Db\Traits\HasApplicationCollectionTrait;
use Element\Entity\Db\Traits\HasCompetenceCollectionTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Metier\Entity\Db\Metier;
use UnicaenEtat\Entity\Db\HasEtatInterface;
use UnicaenEtat\Entity\Db\HasEtatTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

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

    public function __construct()
    {
        $this->activites = new ArrayCollection();
        $this->applications = new ArrayCollection();
        $this->competences = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return Metier|null
     */
    public function getMetier() : ?Metier
    {
        return $this->metier;
    }

    /**
     * @param Metier|null $metier
     * @return FicheMetier
     */
    public function setMetier(?Metier $metier) : FicheMetier
    {
        $this->metier = $metier;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function hasExpertise() : ?bool
    {
        return $this->hasExpertise;
    }

    /**
     * @param bool $has
     * @return $this
     */
    public function setExpertise(?bool $has = false) : FicheMetier
    {
        $this->hasExpertise = $has;
        return $this;
    }

     /**
     * @return string
     */
    public function getMissionsPrincipales() : string
    {
        $texte = '<ul>';
        $activites = $this->getActivites();
        usort($activites, function (FicheMetierActivite $a, FicheMetierActivite $b) {return $a->getPosition() > $b->getPosition();});
        foreach ($activites as $activite) {
            $texte .= '<li>'.$activite->getActivite()->getLibelle().'</li>';
        }
        $texte .= '</ul>';
        return $texte;
    }

    /** ACTIVITE ******************************************************************************************************/

    /**
     * Pour simplifier le tri selon la position est fait à ce niveau
     * @return FicheMetierActivite[]
     */
    public function getActivites() : array
    {
        $activites =  $this->activites->toArray();
        usort($activites, function (FicheMetierActivite $a, FicheMetierActivite $b) { return $a->getPosition() > $b->getPosition();});
        return $activites;
    }

    /** FONCTION POUR MACRO *******************************************************************************************/

    /**
     * @return string
     */
    public function getIntitule() : string
    {
        $metier = $this->getMetier();
        if ($metier === null) return "Aucun métier est associé.";
        return $metier->getLibelle();
    }

    /**
     * @return string
     */
    public function getMissions() : string
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

    /**
     * @return string
     */
    public function getCompetences() : string
    {
        $competences = $this->getCompetenceListe();

        $texte = "<ul>";
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

        if (empty($competences)) return "";

        $competence = $competences[0];
        $competenceType = "";
        switch($competence->getType()->getId()) {
            case 1 : $competenceType = "Compétences comportementales"; break;
            case 2 : $competenceType = "Compétences opérationnelles"; break;
            case 3 : $competenceType = "Connaissances"; break;
        }

        $texte = "<h3>".$competenceType."</h3>";
        $texte .= "<ul>";
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

    /**
     * @return string
     */
    public function getApplicationsAffichage() : string
    {
        $applications = $this->getApplicationListe();

        $texte = "<ul>";
        /** @var ApplicationElement $applicationElement */
        foreach ($applications as $applicationElement) {
            $application = $applicationElement->getApplication();
            $texte .= "<li>".$application->getLibelle()."</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }
}