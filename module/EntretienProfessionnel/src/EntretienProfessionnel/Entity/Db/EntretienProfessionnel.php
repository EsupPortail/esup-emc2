<?php

namespace EntretienProfessionnel\Entity\Db;

use Application\Entity\Db\Agent;
use Application\Entity\HasAgentInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Provider\Observation\EntretienProfessionnelObservations;
use EntretienProfessionnel\Provider\Validation\EntretienProfessionnelValidations;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use RuntimeException;
use UnicaenEvenement\Entity\HasEvenementsInterface;
use UnicaenEvenement\Entity\HasEvenementsTrait;
use UnicaenObservation\Entity\Interface\HasObservationsInterface;
use UnicaenObservation\Entity\Trait\HasObservationsTrait;
use UnicaenAutoform\Entity\Db\FormulaireInstance;
use UnicaenEtat\Entity\Db\HasEtatsInterface;
use UnicaenEtat\Entity\Db\HasEtatsTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use UnicaenValidation\Entity\HasValidationsInterface;
use UnicaenValidation\Entity\HasValidationsTrait;

class EntretienProfessionnel implements HistoriqueAwareInterface, ResourceInterface, HasAgentInterface,
    HasEtatsInterface, HasValidationsInterface, HasObservationsInterface,
    HasEvenementsInterface
{
    use HistoriqueAwareTrait;
    use HasEtatsTrait;
    use HasValidationsTrait;
    use HasObservationsTrait;
    use HasEvenementsTrait;

    const FORMULAIRE_CREP                   = 'CREP';
    const FORMULAIRE_CREF                   = 'CREF';

    public function generateTag() : string
    {
        return (implode('_', [$this->getResourceId(), $this->getId()]));
    }

    public function getResourceId() : string
    {
        return 'EntretienProfessionnel';
    }

    private ?int $id = null;
    private ?Agent $agent = null;
    private ?Agent $responsable = null;
    private ?Campagne $campagne = null;
    private ?DateTime $dateEntretien = null;
    private ?string $lieu = null;
    private ?float $dureeEstimee = null;

    private ?FormulaireInstance $formulaireInstance = null;
    private ?FormulaireInstance $formationInstance = null;

    private Collection $sursis;
    private Collection $recours;
    private Collection $observateurs;

    private ?string $token = null;
    private ?DateTime $acceptation = null;

    /** Pour la mention facultatif */
    private ?string $statut = null;

    public function __construct()
    {
        $this->etats = new ArrayCollection();
        $this->observations = new ArrayCollection();
        $this->sursis = new ArrayCollection();
        $this->validations = new ArrayCollection();
        $this->recours = new ArrayCollection();
        $this->observateurs = new ArrayCollection();
        $this->initEvenements();
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getAgent() : ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent) : void
    {
        $this->agent = $agent;
    }

    public function getResponsable() : ?Agent
    {
        return $this->responsable;
    }

    public function setResponsable(?Agent $responsable) : void
    {
        $this->responsable = $responsable;
    }

    public function getCampagne()  : ?Campagne
    {
        return $this->campagne;
    }

    public function setCampagne(?Campagne $campagne) : void
    {
        $this->campagne = $campagne;
    }

    public function getDateEntretien() : ?DateTime
    {
        return $this->dateEntretien;
    }

    public function setDateEntretien(?DateTime $dateEntretien) : void
    {
        $this->dateEntretien = $dateEntretien;
    }

    public function getLieu() : ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu) : void
    {
        $this->lieu = $lieu;
    }

    public function getDureeEstimee(): ?float
    {
        return $this->dureeEstimee;
    }

    public function setDureeEstimee(?float $dureeEstimee): void
    {
        $this->dureeEstimee = $dureeEstimee;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): void
    {
        $this->statut = $statut;
    }

    /** FONCTIONS ***********************/

    public function isComplete() : bool
    {
        return $this->isEtatActif(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT);
    }

    public function getFormulaireInstance() : ?FormulaireInstance
    {
        return $this->formulaireInstance;
    }

    public function setFormulaireInstance(?FormulaireInstance $formulaireInstance) : EntretienProfessionnel
    {
        $this->formulaireInstance = $formulaireInstance;
        return $this;
    }

    public function getFormationInstance() : ?FormulaireInstance
    {
        return $this->formationInstance;
    }

    public function setFormationInstance(?FormulaireInstance $formationInstance) : EntretienProfessionnel
    {
        $this->formationInstance = $formationInstance;
        return $this;
    }

    public function getMaxSaisiEntretien() : ?DateTime
    {
        $date = DateTime::createFromFormat('d/m/Y H:i:s', $this->getDateEntretien()->format('d/m/Y 23:59:59'));
        return $date;

    }

    public function getToken() : ?string
    {
        return $this->token;
    }

    public function setToken(?string $token) : EntretienProfessionnel
    {
        $this->token = $token;
        return $this;
    }

    public function getAcceptation() : ?DateTime
    {
        return $this->acceptation;
    }

    public function setAcceptation(?DateTime $acceptation) : EntretienProfessionnel
    {
        $this->acceptation = $acceptation;
        return $this;
    }

    /** @return Recours[] */
    public function getRecours(): array
    {
        return $this->recours->toArray();
    }

    public function getRecoursActif(): ?Recours
    {
        $recours = $this->getRecours();
        $actif = null;
        foreach ($recours as $r) {
            if ($r->estNonHistorise()) {
                if ($actif !== null) throw new RuntimeException("Plusieurs recours actifs pour l'entretien #");
                $actif = $r;
            }
        }
        return $actif;
    }

    public function getObservateurs(bool $withHisto = false): array
    {
        $observateurs =  $this->observateurs->toArray();
        if (!$withHisto) {
            $observateurs = array_filter($observateurs, function (Observateur $observateur) {return $observateur->estNonHistorise();});
        }
        return $observateurs;
    }

    /** PREDICATS *****************************************************************************************************/

    public function isAgent(?Agent $agent) : bool
    {
        return $agent === $this->getAgent();
    }

    public function isReponsable(?Agent $agent) : bool
    {
        return $agent === $this->getResponsable();
    }

    /** MACROS ********************************************************************************************/

    /** @noinspection PhpUnused */
    public function toStringLieu() : string {
        if ($this->lieu !== null) return $this->lieu;
        return "Aucun lieu donné";
    }

    /** @noinspection PhpUnused */
    public function toStringDate() : string {
        if ($this->getDateEntretien() !== null) return $this->dateEntretien->format('d/m/Y à H:i');
        return "Aucune date donnée";
    }

    /** @noinspection PhpUnused */
    public function toStringAgent() : string {
        if ($this->getAgent() !== null) return $this->getAgent()->getDenomination();
        return "Aucun agent donné";
    }

    /** @noinspection PhpUnused */
    public function toStringResponsable() : string {
        if ($this->getResponsable() !== null) return $this->getResponsable()->getDenomination();
        return "Aucun responsable d'entretien donné";
    }

    /** @noinspection PhpUnused */
    public function toStringReponsableNomUsage() : string
    {
        /** @var Agent $agent */
        $agent = $this->getResponsable();
        if ($agent->getNomUsuel() === null) return "Aucun nom d'usage";
        return $agent->getNomUsuel();
    }

    /** @noinspection PhpUnused */
    public function toStringReponsableNomFamille() : string
    {
        /** @var Agent $agent */
        $agent = $this->getResponsable();
        if ($agent->getNomFamille() === null) return "Aucun nom de famille";
        return $agent->getNomFamille();
    }

    /** @noinspection PhpUnused */
    public function toStringReponsablePrenom() : string
    {
        /** @var Agent $agent */
        $agent = $this->getResponsable();
        if ($agent->getPrenom() === null) return "Aucun prénom";
        return $agent->getPrenom();
    }

    /** @noinspection PhpUnused */
    public function toStringReponsableCorpsGrade() : string
    {
        /** @var Agent $agent */
        $agent = $this->getResponsable();
        $grades = ($agent->getGradesActifs()) ? $agent->getGradesActifs() : null;

        if ($grades === null) return "Aucune date";

        $texte = "";
        foreach ($grades as $grade) {
            $texte .= $grade->getCorps()->getLibelleLong() . "  - " . $grade->getGrade()->getLibelleLong();
        }
        return $texte;
    }

    /** @noinspection PhpUnused */
    public function toStringReponsableStructure() : string
    {
        /** @var Agent $agent */
        $agent = $this->getResponsable();
        $structure = ($agent->getAffectationPrincipale())?$agent->getAffectationPrincipale()->getStructure():null;

        if ($structure === null) return "Aucune Structure";
        if ($structure->getNiv2() === null or $structure === $structure->getNiv2()) return $structure->getLibelleLong();

        return $structure->getNiv2()->getLibelleLong();
    }

    /** @noinspection PhpUnused */
    public function toStringReponsableIntitulePoste() : string
    {
        /** @var Agent $responsble */
        $responsble = $this->getResponsable();
        $fiche = $responsble->getFichePosteBest();

        if ($fiche === null) return "Aucune fiche de poste EMC2";
        $metier  = $fiche->getLibelleMetierPrincipal();
        $complement = $fiche->getLibelle();
        if ($metier === null) return "Aucun métier principal dans la fiche [".$complement."]";

        if ($complement) return $metier . " rattaché à " . $complement;
        return $metier;
    }

    /** @noinspection PhpUnused */
    public function  toStringValidationAgent() : string {
        $validation = $this->getValidationActiveByTypeCode(EntretienProfessionnelValidations::VALIDATION_AGENT);
        if ($validation !== null) {
            return $validation->getHistoCreation()->format('d/m/Y à H:i'). " par " .$validation->getHistoCreateur()->getDisplayName();
        }
        return "Aucune validation de l'agent";
    }

    /** @noinspection PhpUnused */
    public function  toStringValidationResponsable() : string {
        $validation = $this->getValidationActiveByTypeCode(EntretienProfessionnelValidations::VALIDATION_RESPONSABLE);
        if ($validation !== null) {
            return $validation->getHistoCreation()->format('d/m/Y à H:i'). " par " .$validation->getHistoCreateur()->getDisplayName();
        }
        return "Aucune validation du responsable d'entretien";
    }

    /** @noinspection PhpUnused */
    public function  toStringValidationHierarchie() : string {
        $validation = $this->getValidationActiveByTypeCode(EntretienProfessionnelValidations::VALIDATION_DRH);
        if ($validation !== null) {
            return $validation->getHistoCreation()->format('d/m/Y à H:i'). " par " .$validation->getHistoCreateur()->getDisplayName();
        }
        return "Aucune validation du responsable hiérarchique";
    }

    /** @noinspection PhpUnused */
    public function toStringCR_Entretien() : string {

        if ($this->formulaireInstance !== null) {
            return $this->formulaireInstance->prettyPrint();
        }
        return "Aucune formulaire";
    }

    /** @noinspection PhpUnused */
    public function toStringCR_Formation() : string {

        if ($this->formationInstance !== null) {
            return $this->formationInstance->prettyPrint();
        }
        return "Aucune formulaire";
    }

    /** @noinspection PhpUnused */
    public function toStringObservationEntretien() : string {
        $observation = $this->getObservationWithTypeCode(EntretienProfessionnelObservations::OBSERVATION_AGENT_ENTRETIEN);
        if ($observation) return $observation->getObservation();
        return "Aucune observation";
    }

    /** @noinspection PhpUnused */
    public function toStringObservationFormation() : string {
        $observation = $this->getObservationWithTypeCode(EntretienProfessionnelObservations::OBSERVATION_AGENT_FORMATION);
        if ($observation) return $observation->getObservation();
        return "Aucune observation";
    }

    /** @noinspection PhpUnused */
    public function toStringObservationPerspective() : string {
        $observation = $this->getObservationWithTypeCode(EntretienProfessionnelObservations::OBSERVATION_AGENT_PERSPECTIVE);
        if ($observation) return $observation->getObservation();
        return "Aucune observation";
    }

    /** @noinspection PhpUnused */
    public function toStringObservationAutorite() : string {
        $observation = $this->getObservationWithTypeCode(EntretienProfessionnelObservations::OBSERVATION_AUTORITE);
        if ($observation) return $observation->getObservation();
        return "Aucune observation";
    }

    /** @noinspection PhpUnused */
    public function toStringObservationFinale() : string {
        $observation = $this->getObservationWithTypeCode(EntretienProfessionnelObservations::OBSERVATION_AGENT_FINALE);
        if ($observation) return $observation->getObservation();
        return "Aucune observation";
    }


    /** @noinspection PhpUnused */
    public function toString_CREP_projet() : string {
        $res = $this->formulaireInstance->fetchChampReponseByMotsClefs(['CREP', 'projet']);
        if (trim($res) === '' ) return "Non";
        return "Oui";
    }

    /** @noinspection PhpUnused */
    public function toString_CREP_encadrement() : string {
        $res = $this->formulaireInstance->fetchChampReponseByMotsClefs(['CREP', 'encadrement']);
        if (trim($res) === '' ) return "Non";
        return "Oui";
    }

    /** @noinspection PhpUnused */
    public function toString_CREP_encadrementA() : string {
        $res = $this->formulaireInstance->fetchChampReponseByMotsClefs(['CREP', 'encadrement_A']);
        if (trim($res) === '' ) return "0";
        return $res;
    }

    /** @noinspection PhpUnused */
    public function toString_CREP_encadrementB() : string {
        $res = $this->formulaireInstance->fetchChampReponseByMotsClefs(['CREP', 'encadrement_B']);
        if (trim($res) === '' ) return "0";
        return $res;
    }

    /** @noinspection PhpUnused */
    public function toString_CREP_encadrementC() : string {
        $res = $this->formulaireInstance->fetchChampReponseByMotsClefs(['CREP', 'encadrement_C']);
        if (trim($res) === '' ) return "0";
        return $res;
    }

    /** @noinspection PhpUnused */
    public function toString_CREP_experiencepro() : string {
        $texte1 =  $this->formulaireInstance->fetchChampReponseByMotsClefs(['CREP', 'exppro_1']);
        $texte2 =  $this->formulaireInstance->fetchChampReponseByMotsClefs(['CREP', 'exppro_2']);

        $res = $texte1;
        if ($texte2 AND trim($texte2) !== '') {
            $res = "<p>Autres : " . $texte2 . "</p>";
        }
        return $res;
    }

    /** @noinspection PhpUnused */
    public function toStringCREP_Champ($motsClefs): string
    {
//        $mots = explode(";", $motsClefs);
//        $texte = $this->formulaireInstance->fetchChampReponseByMotsClefs($mots);
        $texte = $this->formulaireInstance->fetchChampReponseByMotsClefs($motsClefs);
        return str_replace("_"," ",$texte);
    }

    /** MACRO du CREF *************************************************************************************************/

    /** @noinspection PhpUnused */
    public function toStringCREF_Champ($motsClefs) : ?string
    {
//        $mots = explode(";", $motsClefs);
        return $this->formationInstance->fetchChampReponseByMotsClefs($motsClefs);
    }

    /** @noinspection PhpUnused */
    public function toStringCREF_Champs($motsClefs) : ?string
    {
//        $mots = explode(";", $motsClefs);
        return $this->formationInstance->fetchChampsReponseByMotsClefs($motsClefs);
    }

    /** @noinspection PhpUnused */
    public function toStringCompetencesTechniques() : string {
        $reponses = $this->getFormulaireInstance()->fetchChampReponseByMotsClefs(['CREP','3.1.1']);
        $items = explode(";",$reponses);
        $texte = "<ul>";
        foreach ($items as $item) {
            switch ((int) $item) {
            case 1 :
                $texte .= "<li>maîtrise technique ou expertise scientifique du domaine d’activité</li>";
                break;
            case 2 :
                $texte .= "<li>connaissance de l’environnement professionnel et capacité à s’y situer</li>";
                break;
            case 3 :
                $texte .= "<li>capacité à appréhender les enjeux des dossiers et des affaires traités</li>";
                break;
            case 4 :
                $texte .= "<li>capacité d’anticipation et d’innovation</li>";
                break;
            case 5 :
                $texte .= "<li>implication dans l’actualisation de ses connaissances professionnelles, volonté de s’informer et de se former</li>";
                break;
            case 6 :
                $texte .= "<li>capacité d’analyse, de synthèse et de résolution des problèmes</li>";
                break;
            case 7 :
                $texte .= "<li>qualités d’expression écrite</li>";
                break;
            case 8 :
                $texte .= "<li>qualités d’expression orale</li>";
                break;
            }
        }
        $texte .= "</ul>";
        return $texte;
    }

    /** @noinspection PhpUnused */
    public function toStringActiviteService() : string {
        $reponses = $this->getFormulaireInstance()->fetchChampReponseByMotsClefs(['CREP','3.1.2']);
        $items = explode(";",$reponses);
        $texte = "<ul>";
        foreach ($items as $item) {
            switch ((int) $item) {
                case 1:
                    $texte .= "<li>sens du service et conscience professionnelle</li>";
                    break;
                case 2:
                    $texte .= "<li>capacité à respecter l’organisation collective du travail</li>";
                    break;
                case 3:
                    $texte .= "<li>rigueur et efficacité (fiabilité et qualité du travail effectué, respect des délais, des normes et des procédures, sens de l’organisation, sens de la méthode, attention portée à la qualité du service rendu)</li>";
                    break;
                case 4:
                    $texte .= "<li>aptitude à exercer des responsabilités particulières ou à faire face à des sujétions spécifiques au poste occupé</li>";
                    break;
                case 5:
                    $texte .= "<li>capacité à partager l’information, à transférer les connaissances et à rendre compte</li>";
                    break;
                case 6:
                    $texte .= "<li>dynamisme et capacité à réagir</li>";
                    break;
                case 7:
                    $texte .= "<li>sens des responsabilités</li>";
                    break;
                case 8:
                    $texte .= "<li>capacité de travail</li>";
                    break;
                case 9:
                    $texte .= "<li>capacité à s’investir dans des projets</li>";
                    break;
                case 10:
                    $texte .= "<li>contribution au respect des règles d’hygiène et de sécurité</li>";
                    break;
            }
        }
        $texte .= "</ul>";
        return $texte;
    }

    /** @noinspection PhpUnused */
    public function toStringCompetencesPersonnelles() : string {
        $reponses = $this->getFormulaireInstance()->fetchChampReponseByMotsClefs(['CREP','3.1.3']);
        $items = explode(";",$reponses);
        $texte = "<ul>";
        foreach ($items as $item) {
            switch ((int) $item) {
            case 1 :
                $texte .= "<li>autonomie, discernement et sens des initiatives dans l’exercice de ses attributions</li>";
                break;
            case 2 :
                $texte .="<li>capacité d’adaptation</li>";
                break;
            case 3 :
                $texte .="<li>capacité à travailler en équipe</li>";
                break;
            case 4 :
                $texte .="<li>aptitudes relationnelles (avec le et dans l’environnement professionnel), notamment maîtrise de soi</li>";
                break;
            }
        }
        $texte .= "</ul>";
        return $texte;
    }

    /** @noinspection PhpUnused */
    public function toStringEncadrementConduite() : string {
        $reponses = $this->getFormulaireInstance()->fetchChampReponseByMotsClefs(['CREP','3.1.4']);
        $items = explode(";",$reponses);
        $texte = "<ul>";
        foreach ($items as $item) {
            switch ((int) $item) {
                case 1 :
                    $texte .= "<li>capacité à animer une équipe ou un réseau</li>";
                    break;
                case 2 :
                    $texte .= "<li>capacité à identifier, mobiliser et valoriser les compétences individuelles et collectives</li>";
                    break;
                case 3 :
                    $texte .= "<li>capacité d’organisation et de pilotage</li>";
                    break;
                case 4 :
                    $texte .= "<li>aptitude à la conduite de projets</li>";
                    break;
                case 5 :
                    $texte .= "<li>capacité à déléguer</li>";
                    break;
                case 6 :
                    $texte .= "<li>capacité à former</li>";
                    break;
                case 7 :
                    $texte .= "<li>aptitude au dialogue, à la communication et à la négociation</li>";
                    break;
                case 8 :
                    $texte .= "<li>aptitude à prévenir, arbitrer et gérer les conflits</li>";
                    break;
                case 9 :
                    $texte .= "<li>aptitude à faire des propositions, à prendre des décisions et à les faire appliquer</li>";
                    break;

            }
        }
        $texte .= "</ul>";
        return $texte;
    }

    public function isAccepte(): bool
    {
        $validations = $this->getEtatsByCode(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER, false);
        return ($validations !== null && !$validations->isEmpty());
    }

    public function isValideResponsable(): bool
    {
        $validations = $this->getEtatsByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_RESPONSABLE, false);
        return ($validations !== null && !$validations->isEmpty());
    }

    public function isDepasse(): bool
    {
        return ($this->getDateEntretien() < (new DateTime()));
    }

    /** @noinspection PhpUnused ENTRETIEN#Facultatif */
    public function mentionFacultatif(): string
    {
        if ($this->statut === "facultatif") return "L'agent·e n'avait pas d'obligation à passer l'entretien pour cette campagne.";
        return "";
    }

    public function prettyPrint(): string
    {
        return $this->getAgent()->getDenomination() . " - entretien planifié le ". $this->getDateEntretien()->format('d/m/Y');
    }
}