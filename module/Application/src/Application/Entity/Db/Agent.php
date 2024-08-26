<?php

namespace Application\Entity\Db;

use Carriere\Entity\Db\Grade;
use FichePoste\Provider\Etat\FichePosteEtats;
use Carriere\Entity\Db\NiveauEnveloppe;
use Doctrine\Common\Collections\Collection;
use Element\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Element\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Application\Entity\Db\MacroContent\AgentMacroTrait;
use Application\Entity\Db\Traits\DbImportableAwareTrait;
use Element\Entity\Db\Traits\HasApplicationCollectionTrait;
use Element\Entity\Db\Traits\HasCompetenceCollectionTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use EntretienProfessionnel\Entity\Db\AgentForceSansObligation;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use Exception;
use Fichier\Entity\Db\Fichier;
use Formation\Entity\Db\Interfaces\HasFormationCollectionInterface;
use Formation\Entity\Db\Traits\HasFormationCollectionTrait;
use Structure\Entity\Db\Structure;
use Structure\Entity\Db\StructureAgentForce;
use UnicaenParametre\Entity\Db\Parametre;
use UnicaenUtilisateur\Entity\Db\AbstractUser;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenValidation\Entity\HasValidationsInterface;
use UnicaenValidation\Entity\HasValidationsTrait;

class Agent implements
    ResourceInterface,
    HasApplicationCollectionInterface, HasCompetenceCollectionInterface, HasFormationCollectionInterface,
    HasValidationsInterface
{
    use DbImportableAwareTrait;
    use AgentServiceAwareTrait;
    use HasApplicationCollectionTrait;  use HasCompetenceCollectionTrait;  use HasFormationCollectionTrait;
    use HasValidationsTrait;
    use AgentMacroTrait;

    const ROLE_AGENT         = 'Agent';
    const ROLE_SUPERIEURE    = 'Supérieur·e hiérarchique direct·e';
    const ROLE_AUTORITE      = 'Autorité hiérarchique';

    public function getResourceId() : string
    {
        return 'Agent';
    }

    private ?string $id = null;
    private ?string $prenom = null;
    private ?string $nomUsuel = null;
    private ?string $nomFamille = null;
    private ?string $sexe = null;
    private ?DateTime $dateNaissance = null;
    private ?string $login = null;
    private ?string $harpId = null;
    private ?string $email = null;
    private ?string $tContratLong = null;

    private Collection $affectations;   /** AgentAffectation[] */
    private Collection $echelons;       /** AgentEchelon[] */
    private Collection $grades;         /** AgentGrade[] */
    private Collection $quotites;       /** AgentQuotite[] */
    private Collection $statuts;        /** AgentStatut[] */

    private ?AbstractUser $utilisateur = null;

    private Collection $fiches;         /** FichePoste[] */
    private Collection $entretiens;     /** EntretienProfessionnel[] */
    private Collection $fichiers;       /** Fichier[] */
    private Collection $missionsSpecifiques; /** AgentMissionSpecifique[] */
    private Collection $structuresForcees;  /** StructureAgentForce  */
    private Collection $forcesSansObligation;  /** StructureAgentForce  */

    private Collection $autorites;      /** AgentAutorite[] */
    private Collection $superieurs;     /** AgentSuperieur[] */

    public function __construct()
    {
        $this->fiches = new ArrayCollection();
        $this->statuts = new ArrayCollection();
        $this->missionsSpecifiques = new ArrayCollection();
        $this->fichiers = new ArrayCollection();
        $this->echelons = new ArrayCollection();
        $this->grades = new ArrayCollection();
        $this->structuresForcees = new ArrayCollection();
        $this->forcesSansObligation = new ArrayCollection();

        $this->affectations = new ArrayCollection();
        $this->autorites = new ArrayCollection();
        $this->superieurs = new ArrayCollection();
        $this->validations = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function generateTag()  : string
    {
        return 'Agent_' . $this->getId();
    }

    /** Accesseur en lecteur de l'identification (importer de la base source) ********************/

    /** Todo remettre un type une fois l'identifiant stabilisé (URN:string ? UCN: int) **/
    public function getId()
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getPrenom() : ?string
    {
        return $this->prenom;
    }

    public function getNomUsuel() : ?string
    {
        return $this->nomUsuel;
    }

    public function getNomFamille() : ?string
    {
        return $this->nomFamille;
    }

    public function getSexe() : ?string
    {
        return $this->sexe;
    }

    public function getDateNaissance(): ?DateTime
    {
        return $this->dateNaissance;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getHarpId() : ?int
    {
        return $this->harpId;
    }


    public function isContratLong() : bool
    {
        return $this->tContratLong === 'O';
    }

    /** Collections importées *****************************************************************************************/

    /** @return AgentAffectation[] */
    public function getAffectations(?DateTime $date = null, bool $histo = false) : array
    {
        $affectations = $this->affectations->toArray();
        if ($histo === false) $affectations = array_filter($affectations, function (AgentAffectation $ae) { return !$ae->isDeleted();});
        if ($date  !== null)  $affectations = array_filter($affectations, function (AgentAffectation $ae) use ($date) { return ($ae->estEnCours($date));});

        usort($affectations, function (AgentAffectation $a, AgentAffectation $b) { return $a->getDateDebut() <=> $b->getDateDebut(); });
        return $affectations;
    }

    /** @return AgentAffectation[] */
    public function getAffectationsActifs(?DateTime $date = null, ?array $structures = null) : array
    {
        if ($date === null) $date = (new DateTime());
        $affectations =  $this->getAffectations($date);
        if ($structures !== null) $affectations = array_filter($affectations, function (AgentAffectation $a) use ($structures) { return in_array($a->getStructure(), $structures);});
        return $affectations;
    }

    //TODO A reecrire car peut être multiple ...
    public function getAffectationPrincipale(?DateTime $date = null) : ?AgentAffectation
    {
        if ($date === null) {
            $date = new DateTime();
        }
        /** @var AgentAffectation $affectation */
        foreach ($this->getAffectations() as $affectation) {
            if ($affectation->isPrincipale()
                and ($affectation->getDateDebut() === null OR $affectation->getDateDebut() <= $date)
                and ($affectation->getDateFin() === null OR $affectation->getDateFin() >= $date)
            ) {
                return $affectation;
            }
        }
        return null;
    }

    /** @return AgentEchelon[] */
    public function getEchelons(?DateTime $date = null, bool $histo = false) : array
    {
        $echelons = $this->echelons->toArray();
        if ($histo === false) $echelons = array_filter($echelons, function (AgentEchelon $ae) { return !$ae->isDeleted();});
        if ($date  !== null)  $echelons = array_filter($echelons, function (AgentEchelon $ae) use ($date) { return ($ae->estEnCours($date));});

        usort($echelons, function (AgentEchelon $a, AgentEchelon $b) { return $a->getDateDebut() <=> $b->getDateDebut(); });
        return $echelons;
    }

    /** @return AgentEchelon[] */
    public function getEchelonsActifs(?DateTime $date = null) : array
    {
        if ($date === null) $date = (new DateTime());
        return $this->getEchelons($date);
    }

    /** @return AgentGrade[] */
    public function getGrades(?DateTime $date = null, bool $histo = false) : array
    {
        $grades = $this->grades->toArray();
        if ($histo === false) $grades = array_filter($grades, function (AgentGrade $ag) { return !$ag->isDeleted();});
        if ($date  !== null)  $grades = array_filter($grades, function (AgentGrade $ag) use ($date) { return ($ag->estEnCours($date));});

        usort($grades, function (AgentGrade $a, AgentGrade $b) { return $a->getDateDebut() <=> $b->getDateDebut(); });
        return $grades;
    }

    /** @return AgentGrade[] */
    public function getGradesActifs(?DateTime $date = null) : array
    {
        if ($date === null) $date = (new DateTime());
        return $this->getGrades($date);
    }

    /** @return AgentQuotite[] */
    public function getQuotites(?DateTime $date = null, bool $histo = false) : array
    {
        $quotites = $this->quotites->toArray();
        if ($histo === false) $quotites = array_filter($quotites, function (AgentQuotite $q) { return !$q->isDeleted(); });
        if ($date  !== null)  $quotites = array_filter($quotites, function (AgentQuotite $q) use ($date) { return ($q->estEnCours($date));});

        usort($quotites, function(AgentQuotite $a, AgentQuotite $b) { return $a->getDateDebut() <=> $b->getDateDebut();});
        return $quotites;
    }

    /** @return AgentQuotite[] */
    public function getQuotitesActives(?DateTime $date = null) : array
    {
        if ($date === null) $date = (new DateTime());
        return $this->getQuotites($date);
    }

    /** @return AgentStatut[] */
    public function getStatuts(?DateTime $date = null, bool $histo = false) : array
    {
        $statuts = $this->statuts->toArray();
        if ($histo === false) $statuts = array_filter($statuts, function (AgentStatut $as) { return (!$as->isDeleted());});
        if ($date  !== null)  $statuts = array_filter($statuts, function (AgentStatut $as) use ($date) { return ($as->estEnCours($date));});

        usort($statuts, function (AgentStatut $a, AgentStatut $b) { return $a->getDateDebut() <=> $b->getDateDebut(); });
        return $statuts;
    }

    /** @return AgentStatut[] */
    public function getStatutsActifs(?DateTime $date = null, ?array $structures = null) : array
    {
        if ($date === null) $date = (new DateTime());
        $statuts = $this->getStatuts($date);
        if ($structures !== null) $statuts = array_filter($statuts, function (AgentStatut $a) use ($structures) { return in_array($a->getStructure(), $structures);});
        return $statuts;
    }

    /** @return AgentGrade[] */
    public function getEmploiTypesActifs(?DateTime $date = null, ?array $structures = null) : array
    {
        if ($date === null) $date = (new DateTime());
        $grades =  $this->getGrades($date);
        if ($structures !== null) $grades = array_filter($grades, function (AgentGrade $a) use ($structures) { return in_array($a->getStructure(), $structures);});
        return $grades;
    }

    /** Prédicats avec temoins ****************************************************************************************/
    // TODO factoriser le comptage ...

    public function isValideAffectation(?Parametre $parametre, ?DateTime $date = null, ?array $structures = null, bool $emptyResult = false): bool
    {
        $temoins = $parametre->getTemoins();

        $count = [];
        $affectations = $this->getAffectationsActifs($date, $structures);
        if (empty($affectations)) return $emptyResult;
        foreach ($affectations as $affectation) {
            foreach (AgentAffectation::TEMOINS as $temoin) {
                if ($affectation->getTemoin($temoin)) {
                    $count[$temoin] = true;
                }
            }
        }

        $keep = true;
        foreach ($temoins['on'] as $temoin) {
            if (!isset($count[$temoin])) {
                $keep = false;
                break;
            }
        }
        foreach ($temoins['off'] as $temoin) {
            if (isset($count[$temoin])) {
                $keep = false;
                break;
            }
        }
        return $keep;
    }

    public function isValideStatut(?Parametre $parametre, ?DateTime $date = null, ?array $structures = null, bool $emptyResult = false): bool
    {
        if ($parametre === null) return true;
        $temoins = $parametre->getTemoins();

        $count = [];
        $statuts = $this->getStatutsActifs($date, $structures);
        if (empty($statuts)) return $emptyResult;

        foreach ($statuts as $statut) {
            foreach (AgentStatut::TEMOINS as $temoin) {
                if ($statut->getTemoin($temoin)) {
                    $count[$temoin] = true;
                }
            }
        }

        $keep = true;
        foreach ($temoins['on'] as $temoin) {
            if (!isset($count[$temoin])) {
                $keep = false;
                break;
            }
        }
        foreach ($temoins['off'] as $temoin) {
            if (isset($count[$temoin])) {
                $keep = false;
                break;
            }
        }
        return $keep;
    }

    public function isValideEmploiType(?Parametre $parametre, ?DateTime $date = null, ?array $structures = null, bool $emptyResult = false): bool
    {
        $temoins = $parametre->getTemoins();

        $count = [];
        $grades = $this->getEmploiTypesActifs($date, $structures);
        if (empty($grades)) return $emptyResult;
        foreach ($grades as $grade) {
            if ($grade->getEmploiType()) $count[$grade->getEmploiType()->getCode()] = true;
        }

        $keep = true;
        foreach ($temoins['on'] as $temoin) {
            if (!isset($count[$temoin])) {
                $keep = false;
                break;
            }
        }
        foreach ($temoins['off'] as $temoin) {
            if (isset($count[$temoin])) {
                $keep = false;
                break;
            }
        }
        return $keep;
    }

    public function isValideGrade(?Parametre $parametre, ?DateTime $date = null, ?array $structures = null, bool $emptyResult = false): bool
    {
        $temoins = $parametre->getTemoins();

        $count = [];
        $grades = $this->getGradesActifs($date);
        if (empty($grades)) return $emptyResult;
        foreach ($grades as $grade) {
            if ($grade->getGrade()) $count[$grade->getGrade()->getLibelleCourt()] = true;
        }

        $keep = true;
        foreach ($temoins['on'] as $temoin) {
            if (!isset($count[$temoin])) {
                $keep = false;
                break;
            }
        }
        foreach ($temoins['off'] as $temoin) {
            if (isset($count[$temoin])) {
                $keep = false;
                break;
            }
        }
        return $keep;
    }

    public function isValideCorps(?Parametre $parametre, ?DateTime $date = null, ?array $structures = null, bool $emptyResult = false): bool
    {
        $temoins = $parametre->getTemoins();

        $count = [];
        $grades = $this->getGradesActifs($date);
        if (empty($grades)) return $emptyResult;
        foreach ($grades as $grade) {
            if ($grade->getCorps()) $count[$grade->getCorps()->getLibelleCourt()] = true;
        }

        $keep = true;
        foreach ($temoins['on'] as $temoin) {
            if (!isset($count[$temoin])) {
                $keep = false;
                break;
            }
        }
        foreach ($temoins['off'] as $temoin) {
            if (isset($count[$temoin])) {
                $keep = false;
                break;
            }
        }
        return $keep;
    }

    /** Autres accesseurs *********************************************************************************************/

    public function getUtilisateur() : ?AbstractUser
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?AbstractUser $utilisateur) : void
    {
        $this->utilisateur = $utilisateur;
    }


    public function getDenomination(bool $prenomFirst = false, bool $nomCap = true, bool $nomBold = false) : ?string
    {
        $prenom = $this->getPrenom();
        $prenom = str_replace("É", "é", $prenom);
        $prenom = str_replace("È", "è", $prenom);
        $nom = (($this->getNomUsuel())??'<em>'.$this->getNomFamille().'</em>');
        if ($nomCap) $nom = strtoupper($nom);
        if ($nomBold) $nom = "<strong>".$nom."</strong>";
        if ($prenomFirst) return  ucwords(strtolower($prenom), "- ") . ' ' . $nom ;
        return  $nom . ' ' . ucwords(strtolower($prenom), "- ");

    }

    public function toString() : string
    {
        return $this->getDenomination();
    }


    /** STRUCTURE *****************************************************************************************************/

    /**
     * @param DateTime|null $date
     * @return Structure[]
     */
    public function getStructures(?DateTime $date = null) : array
    {
        if ($date === null) $date = (new DateTime());

        $structures = [];
        foreach ($this->getAffectationsActifs($date) as $affectation) {
            $structures[$affectation->getStructure()->getId()] = $affectation->getStructure();
        }
        foreach ($this->getStructuresForcees() as $structuresForcee) {
            $structures[$structuresForcee->getStructure()->getId()] = $structuresForcee->getStructure();
        }
        return $structures;
    }

    /** SANS OBLIGATION ***********************************************************************************************/

    public function isForceSansObligation(Campagne $campagne): bool
    {
        /** @var AgentForceSansObligation $forcage */
        foreach ($this->forcesSansObligation as $forcage) {
            if ($forcage->estNonHistorise() && $forcage->getCampagne() === $campagne && $forcage->getType() === AgentForceSansObligation::FORCE_SANS_OBLIGATION) return true;
        }
        return false;
    }

    public function isForceAvecObligation(Campagne $campagne): bool
    {
        /** @var AgentForceSansObligation $forcage */
        foreach ($this->forcesSansObligation as $forcage) {
            if ($forcage->estNonHistorise() && $forcage->getCampagne() === $campagne && $forcage->getType() === AgentForceSansObligation::FORCE_AVEC_OBLIGATION) return true;
        }
        return false;
    }

    /** FICHES POSTES *************************************************************************************************/

    /**
     * @param bool $incomplement
     * @return FichePoste|null
     * @throws Exception
     */
    public function getFichePosteActive(bool $incomplement = false) : ?FichePoste
    {
        $ficheposte = null;
        foreach ($this->getFiches() as $fiche) {
            if ($fiche->estNonHistorise()
                AND ($incomplement OR $fiche->isComplete())
            ) {
                if ($ficheposte !== null) throw new Exception("Plusieurs fiches de poste actives !");
                $ficheposte = $fiche;

            }
        }
        return $ficheposte;
    }


    /**
     * @return FichePoste[]
     */
    public function getFiches() : array
    {
        return $this->fiches->toArray();
    }

    /**
     * @return Fichier[]
     */
    public function getFichiers() : array
    {
        return $this->fichiers->toArray();
    }

    /**
     * @param Fichier $fichier
     * @return Agent
     */
    public function addFichier(Fichier $fichier) : Agent
    {
        $this->fichiers->add($fichier);
        return $this;
    }

    /**
     * @param Fichier $fichier
     * @return Agent
     */
    public function removeFichier(Fichier $fichier) : Agent
    {
        $this->fichiers->removeElement($fichier);
        return $this;
    }

    /**
     * @param string $nature
     * @return Fichier[]
     */
    public function fetchFiles(string $nature) : array
    {
        $fichiers = $this->getFichiers();
        $fichiers = array_filter($fichiers, function (Fichier $f) use ($nature) {
            return ($f->getHistoDestruction() === null && $f->getNature()->getCode() === $nature);
        });

        return $fichiers;
    }

    /** ENTRETIEN PROFESSIONNEL ***************************************************************************************/

    /**
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensProfessionnels() : array
    {
        $entretiens = [];
        /** @var EntretienProfessionnel $entretien */
        foreach ($this->entretiens as $entretien) {
            if ($entretien->estNonHistorise()) $entretiens[] = $entretien;
        }
        return $entretiens;
    }

    /**
     * @param Campagne $campagne
     * @return EntretienProfessionnel|null
     */
    public function getEntretienProfessionnelByCampagne(Campagne $campagne) : ?EntretienProfessionnel
    {
        /** @var EntretienProfessionnel $entretien */
        foreach ($this->entretiens as $entretien) {
            if ($entretien->getCampagne() === $campagne) return $entretien;
        }
        return null;
    }

    /**  MISSIONS SPECIFIQUES *****************************************************************************************/

    /** @return AgentMissionSpecifique[] */
    public function getMissionsSpecifiques() : array
    {
        $missions = [];
        /** @var AgentMissionSpecifique $mission */
        foreach ($this->missionsSpecifiques as $mission) {
//            if ($mission->estNonHistorise())
            $missions[] = $mission;
        }
        usort($missions, function (AgentMissionSpecifique $a, AgentMissionSpecifique $b) {
            $aDebut = ($a->getDateDebut()) ? $a->getDateDebut()->format('Y-m-d') : "---";
            $aFin = ($a->getDateFin()) ? $a->getDateFin()->format('Y-m-d') : "---";
            $bDebut = ($b->getDateDebut()) ? $b->getDateDebut()->format('Y-m-d') : "---";
            $bFin = ($b->getDateFin()) ? $b->getDateFin()->format('Y-m-d') : "---";
            if ($aDebut !== $bDebut) return $aDebut <=> $bDebut;
            return $aFin <=> $bFin;
        });
        return $missions;
    }

    /** Postes en cours et Fiche de poste en cours ********************************************************************/

    /** Entretien dans moins de 15 jours */
    public function hasEntretienEnCours() : bool
    {
        $now = (new DateTime());
        /** @var EntretienProfessionnel $entretien */
        foreach ($this->entretiens as $entretien) {
            if ($entretien->estNonHistorise()) {
                $date_min = DateTime::createFromFormat('d/m/Y', $entretien->getDateEntretien()->format('d/m/y'));
                $date_min = $date_min->sub(new DateInterval('P15D'));
                $date_max = DateTime::createFromFormat('d/m/Y', $entretien->getDateEntretien()->format('d/m/y'));
                if ($now >= $date_min and $now <= $date_max) return true;
            }
        }
        return false;
    }

    public function getNiveauEnveloppe() : ?NiveauEnveloppe
    {
        $inferieure = null;
        $superieure = null;

        $grades = $this->getGradesActifs();
        foreach ($grades as $grade) {
            $level = $grade->getCorps()->getNiveaux();

            $_inferieure = ($level !== null)?$level->getBorneInferieure():null;
            $_superieure = ($level !== null)?$level->getBorneSuperieure():null;

            if ($inferieure === null OR ($_inferieure AND $_inferieure->getNiveau() < $inferieure->getNiveau())) $inferieure = $_inferieure;
            if ($superieure === null OR ($_superieure AND $_superieure->getNiveau() > $superieure->getNiveau())) $superieure = $_superieure;
        }

        if ($inferieure === null OR $superieure === null) return null;

        $enveloppe = new NiveauEnveloppe();
        $enveloppe->setBorneInferieure($inferieure);
        $enveloppe->setBorneSuperieure($superieure);
        return $enveloppe;
    }




    /** AUTORITES ET SUPERIEURS *****************************************************************************/

    /** @return AgentAutorite[] */
    public function getAutorites(bool $histo = false): array
    {
        /** @var AgentAutorite[] $result */
        $result = $this->autorites->toArray();
        if ($histo === false) {
            $result = array_filter($result, function (AgentAutorite $a) { return $a->estNonHistorise();});
        }
        return $result;
    }

    /** @return AgentSuperieur[] */
    public function getSuperieurs(bool $histo = false): array
    {
        /** @var AgentSuperieur[] $result */
        $result = $this->superieurs->toArray();
        if ($histo === false) {
            $result = array_filter($result, function (AgentSuperieur $a) { return $a->estNonHistorise();});
        }
        return $result;
    }

    /** STRUCTURE AGENT FORCE *****************************************************************************************/

    /**
     * @param bool $keepHisto
     * @return StructureAgentForce[]
     */
    public function getStructuresForcees(bool $keepHisto = false) : array
    {
        $structureAgentForces = $this->structuresForcees->toArray();
        if (! $keepHisto) {
            $structureAgentForces = array_filter($structureAgentForces, function (StructureAgentForce $a) { return $a->estNonHistorise();});
        }
        return $structureAgentForces;
    }

    /** Competence */
    public function getCompetenceDictionnaireComplet() : array
    {
        $dictionnaire = $this->getCompetenceDictionnaire();
        foreach ($this->getFormationDictionnaire() as $item) {
            $formation = $item['entite']->getFormation();
            foreach ($formation->getCompetenceDictionnaire() as $competenceObtenue) {
                $competenceId = $competenceObtenue['entite']->getCompetence()->getId();
                if (isset($dictionnaire[$competenceId])) {
                    $dictionnaire[$competenceId]["raison"][] = $formation;
                } else {
                    $element = [];
                    $element['entite'] = $competenceObtenue['entite'];
                    $element['raison'][] = $formation;
                    $element['conserve'] = true;
                    $dictionnaire[$competenceId] = $element;
                }
            }
        }
        return $dictionnaire;
    }

    public function getApplicationDictionnaireComplet() : array
    {
        $dictionnaire = $this->getApplicationDictionnaire();
        foreach ($this->getFormationDictionnaire() as $item) {
            $formation = $item['entite']->getFormation();
            foreach ($formation->getApplicationDictionnaire() as $applicationObtenue) {
                $applicationId = $applicationObtenue['entite']->getApplication()->getId();
                if (isset($dictionnaire[$applicationId])) {
                    $obtenueNiveau = ($applicationObtenue['entite']->getNiveauMaitrise())?$applicationObtenue['entite']->getNiveauMaitrise()->getNiveau():0;
                    $currentNiveau = ($dictionnaire[$applicationId]['entite']->getNiveauMaitrise())?$dictionnaire[$applicationId]['entite']->getNiveauMaitrise()->getNiveau():0;
                    if ($obtenueNiveau > $currentNiveau) $dictionnaire[$applicationId]['entite'] = $applicationObtenue['entite'];
                    $dictionnaire[$applicationId]["raison"][] = $formation;
                } else {
                    $element = [];
                    $element['entite'] = $applicationObtenue['entite'];
                    $element['raison'][] = $formation;
                    $element['conserve'] = true;
                    $dictionnaire[$applicationId] = $element;
                }
            }
        }
        return $dictionnaire;
    }

    /**
     * @return FichePoste|null
     */
    public function getFichePosteBest() : ?FichePoste
    {
        $best = null;
        /** @var FichePoste $fiche */
        foreach ($this->fiches as $fiche) {
            if ($fiche->isEnCours() AND $fiche->estNonHistorise()) {
                if ($fiche->isEtatActif(FichePosteEtats::ETAT_CODE_SIGNEE)) $best = $fiche;
                if ($fiche->isEtatActif(FichePosteEtats::ETAT_CODE_OK) && ($best === NULL OR !$best->isEtatActif(FichePosteEtats::ETAT_CODE_SIGNEE))) $best = $fiche;
                if ($fiche->isEtatActif(FichePosteEtats::ETAT_CODE_REDACTION) && ($best === NULL OR (!$best->isEtatActif(FichePosteEtats::ETAT_CODE_SIGNEE)) && !$best->isEtatActif(FichePosteEtats::ETAT_CODE_OK))) $best = $fiche;
                if ($fiche->isEtatActif(FichePosteEtats::ETAT_CODE_MASQUEE) && ($best === NULL)) $best = $fiche;
            }
        }
        return $best;
    }

    /** @return Fichier[] */
    public function getFichiersByCode(string $code) : array
    {
        $result = [];
        foreach ($this->getFichiers() as $fichier) {
            if ($fichier->getNature()->getCode() === $code) $result[] = $fichier;
        }
        return $result;
    }

    public function getStatutToString(?DateTime $date = null) : string
    {
        $result = "";

        $statuts =  $this->getStatutsActifs($date);
        $isTitulaire = false;
        foreach ($statuts as $statut) {
            if ($statut->isTitulaire()) { $isTitulaire = true; break;}
        }
        if ($isTitulaire) $result .= "Titulaire ";
        $isCDI = false;
        foreach ($statuts as $statut) {
            if ($statut->isCdi()) { $isCDI = true; break;}
        }
        if ($isCDI) $result .= "C.D.I. ";
        $isCDD = false;
        foreach ($statuts as $statut) {
            if ($statut->isCdd()) { $isCDD = true; break;}
        }
        if ($isCDD) $result .= "C.D.D. ";

        return $result;
    }

    public function isAdministratif(?DateTime $date = null) : bool
    {
        $statuts =  $this->getStatutsActifs($date);
        foreach ($statuts as $statut) if ($statut->isAdministratif()) return true;
        return false;
    }

    public function hasAffectationPrincipale(?Structure $structure): bool
    {
        $affectationPrincipale = $this->getAffectationPrincipale();
        //todo checkbien l'inclusion
        if ($affectationPrincipale AND $affectationPrincipale->getStructure() === $structure) return true;
        return false;
    }

    public function hasGrade(?Grade $grade): bool
    {
        $gradesActifs = $this->getGradesActifs();
        if ($gradesActifs AND !empty($gradesActifs)) {
            foreach ($gradesActifs as $gradeActif) {
                if ($gradeActif->getGrade() === $grade) return true;
            }
        }
        return false;
    }


}