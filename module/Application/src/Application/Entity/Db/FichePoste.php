<?php

namespace Application\Entity\Db;

use Application\Entity\Db\MacroContent\FichePosteMacroTrait;
use Application\Entity\HasAgentInterface;
use DateTime;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Exception\RuntimeException;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class FichePoste implements ResourceInterface, HistoriqueAwareInterface, HasAgentInterface {
    use HistoriqueAwareTrait;
    use DateTimeAwareTrait;
    use FichePosteMacroTrait;

    const TYPE_DEFAULT  = 'DEFAULT';
    const TYPE_INCLUSIF = 'INCLUSIF';
    const TYPE_GENRE    = 'GENRE';
    public function getResourceId()
    {
        return 'FichePoste';
    }

    /** @var int */
    private $id;
    /** @var string */
    private $libelle;
    /** @var Agent */
    private $agent;

    /** @var SpecificitePoste */
    private $specificite;
    /** @var ArrayCollection (Expertise) */
    private $expertises;

    /** @var Poste */
    private $poste;

    /** @var ArrayCollection (FicheTypeExterne)*/
    private $fichesMetiers;
    /** @var ArrayCollection (FicheposteActiviteDescriptionRetiree) */
    private $descriptionsRetirees;
    /** @var ArrayCollection (FicheposteApplicationRetiree) */
    private $applicationsRetirees;
    /** @var ArrayCollection (FicheposteCompetenceRetiree) */
    private $competencesRetirees;

    /** @var array */
    private $dictionnaires;

    public function __invoke()
    {
        $this->fichesMetiers = new ArrayCollection();
        $this->descriptionsRetirees = new ArrayCollection();
        $this->applicationsRetirees = new ArrayCollection();
        $this->competencesRetirees = new ArrayCollection();
    }

    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getLibelle() : ?string
    {
        return $this->libelle;
    }

    /**
     * @param string|null $libelle
     * @return FichePoste
     */
    public function setLibelle(?string $libelle) : FichePoste
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return Agent|null
     */
    public function getAgent() : ?Agent
    {
        return $this->agent;
    }

    /**
     * @param Agent|null $agent
     * @return FichePoste
     */
    public function setAgent(?Agent $agent) : FichePoste
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return SpecificitePoste|null
     */
    public function getSpecificite() : ?SpecificitePoste
    {
        return $this->specificite;
    }

    /**
     * @param SpecificitePoste|null $specificite
     * @return FichePoste
     */
    public function setSpecificite(?SpecificitePoste $specificite) : FichePoste
    {
        $this->specificite = $specificite;
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
     * @return FichePoste
     */
    public function setPoste($poste)
    {
        $this->poste = $poste;
        return $this;
    }

    /**
     * @return FicheTypeExterne[]
     */
    public function getFichesMetiers()
    {
        return $this->fichesMetiers->toArray();
    }

    /**
     * @var FicheTypeExterne $type
     * @return FichePoste
     */
    public function addFicheTypeExterne($type)
    {
        $this->fichesMetiers->add($type);
        return $this;
    }

    /**
     * @var FicheTypeExterne $type
     * @return FichePoste
     */
    public function removeFicheTypeExterne($type)
    {
        $this->fichesMetiers->removeElement($type);
        return $this;
    }

    /**
     * @return FicheTypeExterne
     */
    public function getFicheTypeExternePrincipale() {
        $res = [];
        /** @var FicheTypeExterne $ficheMetier */
        foreach ($this->fichesMetiers as $ficheMetier) {
            if ($ficheMetier->getPrincipale()) {
                $res[] = $ficheMetier;
            }
        }

        $nb = count($res);
        if ( $nb > 1) {
            throw new RuntimeException("Plusieurs fiches metiers sont déclarées comme principale");
        }
        if ($nb === 1) return current($res);
        return null;
    }

    public function getQuotiteTravaillee()
    {
        $somme = 0;
        /** @var FicheTypeExterne $ficheMetier */
        foreach ($this->fichesMetiers as $ficheMetier) {
            $somme += $ficheMetier->getQuotite();
        }
        return $somme;
    }

    /** Descriptions Retirées ******************************************************************************************/

    /** @return ArrayCollection */
    public function getDescriptionsRetirees() {
        return $this->descriptionsRetirees;
    }

    /** @param FicheposteActiviteDescriptionRetiree $description */
    public function addDescriptionRetiree(FicheposteActiviteDescriptionRetiree $description) {
        $this->descriptionsRetirees->add($description);
    }

    /** @param FicheposteActiviteDescriptionRetiree $description */
    public function removeDescriptionRetiree(FicheposteActiviteDescriptionRetiree $description) {
        $this->descriptionsRetirees->removeElement($description);
    }

    public function clearDescriptionsRetirees() {
        $this->descriptionsRetirees->clear();
    }

    /** Competences Retirées ******************************************************************************************/

    /** @return ArrayCollection */
    public function getCompetencesRetirees() {
        return $this->competencesRetirees;
    }

    /** @param FicheposteCompetenceRetiree $competence */
    public function addCompetenceRetiree(FicheposteCompetenceRetiree $competence) {
        $this->competencesRetirees->add($competence);
    }

    /** @param FicheposteCompetenceRetiree $competence */
    public function removeCompetenceRetiree(FicheposteCompetenceRetiree $competence) {
        $this->competencesRetirees->removeElement($competence);
    }

    public function clearCompetencesRetirees() {
        $this->competencesRetirees->clear();
    }

    /** Applications Retirées *****************************************************************************************/

    /** @return ArrayCollection */
    public function getApplicationsRetirees() {
        return $this->applicationsRetirees;
    }

    /** @param FicheposteApplicationRetiree $application */
    public function addApplicationRetiree(FicheposteApplicationRetiree $application) {
        $this->applicationsRetirees->add($application);
    }

    /** @param FicheposteApplicationRetiree $application */
    public function removeApplicationRetiree(FicheposteApplicationRetiree $application) {
        $this->applicationsRetirees->removeElement($application);
    }

    public function clearApplicationsRetirees() {
        $this->applicationsRetirees->clear();
    }

    /** EXPERTISE *****************************************************************************************************/

    /**
     * @return ArrayCollection
     */
    public function getExpertises()
    {
        return $this->expertises;
    }

    /**
     * @param DateTime $date
     * @return Expertise[]
     */
    public function getCurrentExpertises($date = null)
    {
        if ($date === null) $date = $this->getDateTime();

        $expertises = [];
        /** @var Expertise $expertise */
        foreach ($this->expertises as $expertise) {
            if ($expertise->estNonHistorise($date)) {
                $expertises[] = $expertise;
            }
        }
        return $expertises;
    }

    /** Fonctions pour simplifier  */

    /**
     * @param FicheMetierTypeActivite $activite
     * @param DateTime $date
     * @return ActiviteDescription[]
     */
    public function getDescriptions(FicheMetierTypeActivite $activite, DateTime $date)
    {
        $dictionnaire = $activite->getActivite()->getDescriptions($date);
        return $dictionnaire;
    }

    /**
     * @param FicheMetierTypeActivite $FTActivite
     * @param DateTime $date
     * @return ActiviteDescription[]
     */
    public function getDescriptionsConservees(FicheMetierTypeActivite $FTActivite, DateTime $date)
    {
        /** @var ActiviteDescription[] $descriptions */
        $descriptions = $FTActivite->getActivite()->getDescriptions($date);
        $dictionnaire = [];
        foreach ($descriptions as $description) {
            $found = false;
            /** @var FicheposteActiviteDescriptionRetiree $retiree */
            foreach($this->getDescriptionsRetirees() as $retiree) {
                if ($retiree->estNonHistorise() AND $retiree->getDescription() === $description) {
                    $found = true;
                    break;
                }
            }
            if (!$found) $dictionnaire[$description->getId()] = $description;
        }
        return $dictionnaire;
    }

    public function isComplete()
    {
        if (! $this->getAgent()) return false;
//        if (! $this->getPoste()) return false;
        if (! $this->getFicheTypeExternePrincipale()) return false;
        return true;
    }

    public function isVide()
    {
        if ( $this->getAgent()) return false;
//        if ( $this->getPoste()) return false;
        if ( $this->getFicheTypeExternePrincipale()) return false;
        return true;
    }

    public function hasExpertise() {
        /** @var FicheTypeExterne $fichesMetier */
        foreach ($this->fichesMetiers as $fichesMetier) {
            if ($fichesMetier->getFicheType()->hasExpertise()) return true;
        }
        return false;
    }

    /** Fonction pour les affichages dans les documents ***************************************************************/

    public function getConnaissancesAffichage()
    {
        $dictionnaire = [];
        foreach ($this->getFichesMetiers() as $fiche) {
            /** @var Competence $competence */
            foreach ($fiche->getFicheType()->getCompetenceListe() as $competenceElement) {
                $competence = $competenceElement->getCompetence();
                if ($competence->getType()->getId() === CompetenceType::CODE_CONNAISSANCE) $dictionnaire[$competence->getId()] = $competence->getLibelle();
            }
        }

        $texte = "<ul>";
        foreach ($dictionnaire as $id => $libelle) {
            $texte .= "<li>".$libelle."</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }

    public function getCompetencesOperationnellesAffichage()
    {
        $dictionnaire = [];
        foreach ($this->getFichesMetiers() as $fiche) {
            /** @var Competence $competence */
            foreach ($fiche->getFicheType()->getCompetenceListe() as $competenceElement) {
                $competence = $competenceElement->getCompetence();
                if ($competence->getType()->getId() === CompetenceType::CODE_OPERATIONNELLE) $dictionnaire[$competence->getId()] = $competence->getLibelle();
            }
        }

        $texte = "<ul>";
        foreach ($dictionnaire as $id => $libelle) {
            $texte .= "<li>".$libelle."</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }

    public function getCompetencesComportementalesAffichage()
    {
        $dictionnaire = [];
        foreach ($this->getFichesMetiers() as $fiche) {
            /** @var Competence $competence */
            foreach ($fiche->getFicheType()->getCompetenceListe() as $competenceElement) {
                $competence = $competenceElement->getCompetence();
                if ($competence->getType()->getId() === CompetenceType::CODE_COMPORTEMENTALE) $dictionnaire[$competence->getId()] = $competence->getLibelle();
            }
        }

        $texte = "<ul>";
        foreach ($dictionnaire as $id => $libelle) {
            $texte .= "<li>".$libelle."</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }

    public function addDictionnaire(string $clef, $valeur)
    {
        $this->dictionnaires[$clef] = $valeur;
    }

    public function getDictionnaire(string $clef)
    {
        return $this->dictionnaires[$clef];
    }

    /**
     * @param string $type
     * @return string|null
     */
    public function getLibelleMetierPrincipal($type = FichePoste::TYPE_INCLUSIF) : ?string
    {
        if ($this->getFicheTypeExternePrincipale() === null) return null;
        $metier = $this->getFicheTypeExternePrincipale()->getFicheType()->getMetier();

        switch ($type) {
            case FichePoste::TYPE_INCLUSIF : return $metier->getLibelle(true);
            case FichePoste::TYPE_GENRE :
                if ($this->agent !== null)
                if ($this->agent->isHomme() AND $metier->getLibelleMasculin()) return $metier->getLibelleMasculin();
                if ($this->agent->isFemme() AND $metier->getLibelleFeminin()) return $metier->getLibelleFeminin();
                return $metier->getLibelle(true);
            case FichePoste::TYPE_DEFAULT : return $metier->getLibelle(false);
        }

        return $metier->getLibelle();
    }
}