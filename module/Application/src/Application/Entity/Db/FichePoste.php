<?php

namespace Application\Entity\Db;

use Application\Entity\Db\MacroContent\FichePosteMacroTrait;
use Application\Entity\HasAgentInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\FicheMetierMission;
use FicheMetier\Entity\Db\Mission;
use FicheMetier\Entity\Db\MissionActivite;
use FichePoste\Entity\Db\Expertise;
use FichePoste\Entity\Db\MissionAdditionnelle;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use RuntimeException;
use UnicaenEtat\Entity\Db\HasEtatsInterface;
use UnicaenEtat\Entity\Db\HasEtatsTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use UnicaenValidation\Entity\HasValidationsInterface;
use UnicaenValidation\Entity\HasValidationsTrait;

class FichePoste implements ResourceInterface, HistoriqueAwareInterface, HasAgentInterface, HasEtatsInterface, HasValidationsInterface
{
    use FichePosteMacroTrait;
    use HistoriqueAwareTrait;
    use HasEtatsTrait;
    use HasValidationsTrait;

    const TYPE_DEFAULT = 'DEFAULT';
    const TYPE_INCLUSIF = 'INCLUSIF';
    const TYPE_GENRE = 'GENRE';

    public function getResourceId(): string
    {
        return 'FichePoste';
    }

    private ?int $id = null;
    private ?string $libelle = null;
    private ?Agent $agent = null;
    private ?SpecificitePoste $specificite = null;
    private ?int $rifseep = null;
    private ?int $nbi = null;
    private ?DateTime $finValidite = null;

    private Collection $expertises;
    private Collection $fichesMetiers;
    private Collection $descriptionsRetirees;
    private Collection $applicationsRetirees;
    private Collection $competencesRetirees;
    private Collection $missionsAdditionnelles;

    public ?string $codeFonction;

    /** @var array */
    private array $dictionnaires = [];

    public function __construct()
    {
        $this->fichesMetiers = new ArrayCollection();
        $this->descriptionsRetirees = new ArrayCollection();
        $this->applicationsRetirees = new ArrayCollection();
        $this->competencesRetirees = new ArrayCollection();
        $this->missionsAdditionnelles = new ArrayCollection();

        $this->etats = new ArrayCollection();
        $this->validations = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): void
    {
        $this->agent = $agent;
    }

    public function getRifseep(): ?int
    {
        return $this->rifseep;
    }

    public function setRifseep(?int $rifseep): void
    {
        $this->rifseep = $rifseep;
    }

    public function getNbi(): ?int
    {
        return $this->nbi;
    }

    public function setNbi(?int $nbi): void
    {
        $this->nbi = $nbi;
    }

    public function getFinValidite(): ?DateTime
    {
        return $this->finValidite;
    }

    public function setFinValidite(?DateTime $finValidite): void
    {
        $this->finValidite = $finValidite;
    }

    public function isEnCours(?DateTime $date = null): bool
    {
        if ($date === null) $date = new DateTime();
        return ($this->finValidite === null or $date < $this->getFinValidite());
    }

    /** @return FicheTypeExterne[] */
    public function getFichesMetiers(): array
    {
        return $this->fichesMetiers->toArray();
    }

    public function addFicheTypeExterne($type): void
    {
        $this->fichesMetiers->add($type);
    }

    public function removeFicheTypeExterne($type): void
    {
        $this->fichesMetiers->removeElement($type);
    }

    public function getFicheTypeExternePrincipale(): ?FicheTypeExterne
    {
        $res = [];
        /** @var FicheTypeExterne $ficheMetier */
        foreach ($this->fichesMetiers as $ficheMetier) {
            if ($ficheMetier->getPrincipale()) {
                $res[] = $ficheMetier;
            }
        }

        $nb = count($res);
        if ($nb > 1) {
            throw new RuntimeException("Plusieurs fiches metiers sont déclarées comme principale");
        }
        if ($nb === 1) return current($res);
        return null;
    }

    public function getQuotiteTravaillee(): int
    {
        $somme = 0;
        /** @var FicheTypeExterne $ficheMetier */
        foreach ($this->fichesMetiers as $ficheMetier) {
            $somme += $ficheMetier->getQuotite();
        }
        return $somme;
    }

    /** Specificité et missions additionnelles  */

    public function getSpecificite(): ?SpecificitePoste
    {
        return $this->specificite;
    }

    public function setSpecificite(?SpecificitePoste $specificite): void
    {
        $this->specificite = $specificite;
    }

    /** @return MissionAdditionnelle[] */
    public function getMissionsAdditionnelles(bool $historise = false): array
    {
        $result = [];
        /** @var MissionAdditionnelle $missionAdditionnelle */
        foreach ($this->missionsAdditionnelles as $missionAdditionnelle) {
            if (!$historise or $missionAdditionnelle->estNonHistorise()) $result[] = $missionAdditionnelle;
        }
        return $result;
    }

    public function getCodeFonction(): ?string
    {
        return $this->codeFonction;
    }

    public function setCodeFonction(?string $codeFonction): void
    {
        $this->codeFonction = $codeFonction;
    }


    /** Descriptions Retirées ******************************************************************************************/

    /** @return FicheposteActiviteDescriptionRetiree[] */
    public function getDescriptionsRetirees(): array
    {
        return $this->descriptionsRetirees->toArray();
    }

    public function getDescriptionsRetireesByFicheMetierAndActivite(FicheMetier $fichemetier, Mission $activite): array
    {
        $result = [];
        foreach ($this->getDescriptionsRetirees() as $descriptionsRetiree) {
            if ($descriptionsRetiree->getFicheMetier() === $fichemetier and $descriptionsRetiree->getMission() === $activite) {
                $result[] = $descriptionsRetiree;
            }
        }
        return $result;
    }

    public function addDescriptionRetiree(FicheposteActiviteDescriptionRetiree $description): void
    {
        $this->descriptionsRetirees->add($description);
    }

    public function removeDescriptionRetiree(FicheposteActiviteDescriptionRetiree $description): void
    {
        $this->descriptionsRetirees->removeElement($description);
    }

    public function clearDescriptionsRetirees(): void
    {
        $this->descriptionsRetirees->clear();
    }

    /** Competences Retirées ******************************************************************************************/

    /** @return FicheposteCompetenceRetiree[] */
    public function getCompetencesRetirees(): array
    {
        return $this->competencesRetirees->toArray();
    }

    public function addCompetenceRetiree(FicheposteCompetenceRetiree $competence)
    {
        $this->competencesRetirees->add($competence);
    }

    public function removeCompetenceRetiree(FicheposteCompetenceRetiree $competence): void
    {
        $this->competencesRetirees->removeElement($competence);
    }

    public function clearCompetencesRetirees(): void
    {
        $this->competencesRetirees->clear();
    }

    /** Applications Retirées *****************************************************************************************/

    /** @return FicheposteApplicationRetiree[] */
    public function getApplicationsRetirees(): array
    {
        return $this->applicationsRetirees->toArray();
    }

    public function addApplicationRetiree(FicheposteApplicationRetiree $application)
    {
        $this->applicationsRetirees->add($application);
    }

    public function removeApplicationRetiree(FicheposteApplicationRetiree $application)
    {
        $this->applicationsRetirees->removeElement($application);
    }

    public function clearApplicationsRetirees()
    {
        $this->applicationsRetirees->clear();
    }

    /** EXPERTISE *****************************************************************************************************/

    /** @return Expertise[] */
    public function getExpertises(): array
    {
        return $this->expertises->toArray();
    }

    /* @return Expertise[] */
    public function getCurrentExpertises($date = null): array
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

    /* @return MissionActivite[] */
    public function getDescriptions(FicheMetierMission $mission): array
    {
        $dictionnaire = $mission->getMission()->getActivites();
        return $dictionnaire;
    }

    /** @return MissionActivite[] */
    public function getDescriptionsConservees(FicheMetierMission $ficheMetierMission): array
    {
        $activites = $ficheMetierMission->getMission()->getActivites();
        $dictionnaire = [];
        foreach ($activites as $activite) {
            $found = false;
            foreach ($this->getDescriptionsRetirees() as $retiree) {
                if ($retiree->estNonHistorise() and $retiree->getActivite() === $activite) {
                    $found = true;
                    break;
                }
            }
            if (!$found) $dictionnaire[$activite->getId()] = $activite;
        }
        return $dictionnaire;
    }

    public function isComplete(): bool
    {
        if (!$this->getAgent()) return false;
//        if (! $this->getPoste()) return false;
        if (!$this->getFicheTypeExternePrincipale()) return false;
        return true;
    }

    public function isVide(): bool
    {
        if ($this->getAgent()) return false;
//        if ( $this->getPoste()) return false;
        if ($this->getFicheTypeExternePrincipale()) return false;
        return true;
    }

    public function hasExpertise(): bool
    {
        /** @var FicheTypeExterne $fichesMetier */
        foreach ($this->fichesMetiers as $fichesMetier) {
            if ($fichesMetier->getFicheType()->hasExpertise()) return true;
        }
        return false;
    }

    /** Fonction pour les affichages dans les documents ***************************************************************/

    public function addDictionnaire(string $clef, $valeur): void
    {
        $this->dictionnaires[$clef] = $valeur;
    }

    public function getDictionnaire(string $clef)
    {
        return $this->dictionnaires[$clef];
    }

    public function getLibelleMetierPrincipal(string $type = FichePoste::TYPE_INCLUSIF): ?string
    {
        if ($this->getFicheTypeExternePrincipale() === null) return null;
        $metier = $this->getFicheTypeExternePrincipale()->getFicheType()->getMetier();

        switch ($type) {
            case FichePoste::TYPE_INCLUSIF :
                return $metier->getLibelle();
            case FichePoste::TYPE_GENRE :
                if ($this->agent === null) return $metier->getLibelle();
                if ($this->agent->getSexe() === 'M' and $metier->getLibelleMasculin()) return $metier->getLibelleMasculin();
                if ($this->agent->getSexe() === 'F' and $metier->getLibelleFeminin()) return $metier->getLibelleFeminin();
                return $metier->getLibelle();
            case FichePoste::TYPE_DEFAULT :
                return $metier->getLibelle(false);
        }

        return $metier->getLibelle();
    }

    public function generateTag(): string
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