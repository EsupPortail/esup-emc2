<?php

namespace FicheMetier\Entity\Db;

use Application\Entity\Db\ActiviteDescription;
use Application\Entity\Db\FicheMetierActivite;
use Doctrine\Common\Collections\Collection;
use Element\Entity\Db\ApplicationElement;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use Element\Entity\Db\CompetenceType;
use Element\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Element\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Element\Entity\Db\Traits\HasApplicationCollectionTrait;
use Element\Entity\Db\Traits\HasCompetenceCollectionTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Metier\Entity\HasMetierInterface;
use Metier\Entity\HasMetierTrait;
use UnicaenEtat\Entity\Db\HasEtatInterface;
use UnicaenEtat\Entity\Db\HasEtatTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FicheMetier implements HistoriqueAwareInterface, HasEtatInterface, HasMetierInterface,
    HasApplicationCollectionInterface, HasCompetenceCollectionInterface {
    use HistoriqueAwareTrait;
    use HasMetierTrait;
    use HasEtatTrait;
    use HasApplicationCollectionTrait;
    use HasCompetenceCollectionTrait;

    private ?int $id = -1;
    private ?bool $hasExpertise = false;
    private ?string $raison = null;

    private Collection $activites;

    public function __construct()
    {
        $this->activites = new ArrayCollection();
        $this->applications = new ArrayCollection();
        $this->competences = new ArrayCollection();
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function hasExpertise() : bool
    {
        return ($this->hasExpertise === true);
    }

    public function setExpertise(?bool $has = false) : void
    {
        $this->hasExpertise = $has;
    }

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(?string $raison): void
    {
        $this->raison = $raison;
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

    /** @return FicheMetierActivite[] */
    public function getActivites() : array
    {
        $activites =  $this->activites->toArray();
        usort($activites, function (FicheMetierActivite $a, FicheMetierActivite $b) { return $a->getPosition() > $b->getPosition();});
        return $activites;
    }

    /** FONCTION POUR MACRO *******************************************************************************************/

    public function getIntitule() : string
    {
        $metier = $this->getMetier();
        if ($metier === null) return "Aucun métier est associé.";
        return $metier->getLibelle();
    }

    public function getMissions() : string
    {
        $texte = "";
        foreach ($this->getActivites() as $activite) {
            $texte .= "<h3 class='mission-principale'>" . $activite->getActivite()->getLibelle() . "</h3>";
            /** @var ActiviteDescription $descriptions */
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

    public function getConnaissances() : string
    {
        return $this->getComptencesByType(CompetenceType::CODE_CONNAISSANCE);
    }

    public function getCompetencesOperationnelles() : string
    {
        return $this->getComptencesByType(CompetenceType::CODE_OPERATIONNELLE);
    }

    public function getCompetencesComportementales() : string
    {
        return $this->getComptencesByType(CompetenceType::CODE_COMPORTEMENTALE);
    }

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