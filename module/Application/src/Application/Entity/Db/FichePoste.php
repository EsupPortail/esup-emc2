<?php

namespace Application\Entity\Db;

use Application\Entity\Db\MacroContent\FichePosteMacroTrait;
use Application\Entity\HasAgentInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Entity\Db\HasEtatInterface;
use UnicaenEtat\Entity\Db\HasEtatTrait;
use UnicaenValidation\Entity\HasValidationsTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

class FichePoste implements ResourceInterface, HistoriqueAwareInterface, HasAgentInterface, HasEtatInterface {
    use FichePosteMacroTrait;
    use HistoriqueAwareTrait;
    use HasEtatTrait;
    use HasValidationsTrait;

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
    /** @var int */
    private $rifseep;
    /** @var int */
    private $nbi;

    /** @var DateTime|null  */
    private $finValidite;

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
     * @return int
     */
    public function getRifseep(): ?int
    {
        return $this->rifseep;
    }

    /**
     * @param int|null $rifseep
     * @return FichePoste
     */
    public function setRifseep(?int $rifseep): FichePoste
    {
        $this->rifseep = $rifseep;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbi(): ?int
    {
        return $this->nbi;
    }

    /**
     * @param int|null $nbi
     * @return FichePoste
     */
    public function setNbi(?int $nbi): FichePoste
    {
        $this->nbi = $nbi;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getFinValidite(): ?DateTime
    {
        return $this->finValidite;
    }

    /**
     * @param DateTime|null $finValidite
     * @return FichePoste
     */
    public function setFinValidite(?DateTime $finValidite): FichePoste
    {
        $this->finValidite = $finValidite;
        return $this;
    }

    /**
     * @param DateTime|null $date
     * @return bool
     */
    public function isEnCours(?DateTime $date = null) : bool
    {
        if ($date === null) $date = new DateTime();
        return ($this->finValidite === null OR $date < $this->getFinValidite());
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

    /**
     * @param FicheMetier $fichemetier
     * @param Activite $activite
     * @return array
     */
    public function getDescriptionsRetireesByFicheMetierAndActivite(FicheMetier $fichemetier, Activite $activite) : array{
        $result = [];
        /** @var FicheposteActiviteDescriptionRetiree $descriptionsRetiree */
        foreach ($this->getDescriptionsRetirees() as $descriptionsRetiree) {
            if ($descriptionsRetiree->getFicheMetier() === $fichemetier AND $descriptionsRetiree->getActivite() === $activite) {
                $result[] = $descriptionsRetiree;
            }
        }
        return $result;
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
        if ($date === null) $date = (new DateTime());

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
     * @param FicheMetierActivite $activite
     * @param DateTime $date
     * @return ActiviteDescription[]
     */
    public function getDescriptions(FicheMetierActivite $activite, DateTime $date) : array
    {
        $dictionnaire = $activite->getActivite()->getDescriptions($date);
        return $dictionnaire;
    }

    /**
     * @param FicheMetierActivite $FTActivite
     * @param DateTime $date
     * @return ActiviteDescription[]
     */
    public function getDescriptionsConservees(FicheMetierActivite $FTActivite, DateTime $date) : array
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

    public function isComplete() : bool
    {
        if (! $this->getAgent()) return false;
//        if (! $this->getPoste()) return false;
        if (! $this->getFicheTypeExternePrincipale()) return false;
        return true;
    }

    public function isVide() : bool
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
                if ($this->agent === null) return $metier->getLibelle(true);
                if ($this->agent->isHomme() AND $metier->getLibelleMasculin()) return $metier->getLibelleMasculin();
                if ($this->agent->isFemme() AND $metier->getLibelleFeminin()) return $metier->getLibelleFeminin();
                return $metier->getLibelle(true);
            case FichePoste::TYPE_DEFAULT : return $metier->getLibelle(false);
        }

        return $metier->getLibelle();
    }

    /**
     * @return string
     */
    public function generateTag() : string
    {
        return 'FICHEPOSTE_' . $this->getId();
    }

    /** INTERFACE POUR LES COLLECTIONS DE COMPETENCES */
//    public function getCompetenceCollection() {
//        $collection = new ArrayCollection();
//        /** @var FicheTypeExterne $ficheType */
//        foreach ($this->fichesMetiers as $ficheType) {
//            $ficheMetier = $ficheType->getFicheType();
//            foreach ($ficheMetier->getCompetenceCollection() as $competence) $collection->add($competence);
//        }
//        return $collection;
//    }
//
//    public function getCompetenceListe(bool $avecHisto = false)
//    {
//        $dictionnaire = [];
//        foreach ($this->getCompetenceCollection() as $competenceElement) {
//            $element = [];
//            $element['entite'] = $competenceElement;
//            $element['raison'] = null;
//            $element['conserve'] = true;
//            $dictionnaire[] = $element;
//        }
//        return $dictionnaire;
//    }
//
//    public function hasCompetence(Competence $competence) : bool
//    {
//        return false;
//    }
}