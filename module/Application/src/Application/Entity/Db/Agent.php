<?php

namespace Application\Entity\Db;

use Agent\Entity\Db\AgentAffectation;
use Agent\Entity\Db\AgentEchelon;
use Agent\Entity\Db\AgentGrade;
use Agent\Entity\Db\AgentQuotite;
use Agent\Entity\Db\AgentRef;
use Agent\Entity\Db\AgentStatut;
use Application\Entity\Db\MacroContent\AgentMacroTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Carriere\Entity\Db\Corps;
use Carriere\Entity\Db\Grade;
use Carriere\Entity\Db\NiveauEnveloppe;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Element\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Element\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Element\Entity\Db\Traits\HasApplicationCollectionTrait;
use Element\Entity\Db\Traits\HasCompetenceCollectionTrait;
use EntretienProfessionnel\Entity\Db\AgentForceSansObligation;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use Exception;
use FichePoste\Provider\Etat\FichePosteEtats;
use Fichier\Entity\Db\Fichier;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use RuntimeException;
use Structure\Entity\Db\Structure;
use Structure\Entity\Db\StructureAgentForce;
use UnicaenParametre\Entity\Db\Parametre;
use UnicaenSynchro\Entity\Db\IsSynchronisableInterface;
use UnicaenSynchro\Entity\Db\IsSynchronisableTrait;
use UnicaenUtilisateur\Entity\Db\AbstractUser;
use UnicaenValidation\Entity\HasValidationsInterface;
use UnicaenValidation\Entity\HasValidationsTrait;

class Agent implements
    ResourceInterface, IsSynchronisableInterface,
    HasApplicationCollectionInterface, HasCompetenceCollectionInterface,
    HasValidationsInterface
{
    use IsSynchronisableTrait;
    use AgentServiceAwareTrait;
    use HasApplicationCollectionTrait;
    use HasCompetenceCollectionTrait;

    use HasValidationsTrait;
    use AgentMacroTrait;

    const ROLE_AGENT = 'Agent';
    const ROLE_SUPERIEURE = 'Supérieur·e hiérarchique direct·e';
    const ROLE_AUTORITE = 'Autorité hiérarchique';

    public function getResourceId(): string
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

    private Collection $affectations;
    /** AgentAffectation[] */
    private Collection $echelons;
    /** AgentEchelon[] */
    private Collection $grades;
    /** AgentGrade[] */
    private Collection $quotites;
    /** AgentQuotite[] */
    private Collection $statuts;
    /** AgentStatut[] */

    private ?AbstractUser $utilisateur = null;

    private Collection $fiches;
    /** FichePoste[] */
    private Collection $entretiens;
    /** EntretienProfessionnel[] */
    private Collection $fichiers;
    /** Fichier[] */
    private Collection $missionsSpecifiques;
    /** AgentMissionSpecifique[] */
    private Collection $structuresForcees;
    /** StructureAgentForce  */
    private Collection $forcesSansObligation;
    /** StructureAgentForce  */

    private Collection $autorites;
    private Collection $superieurs;
    private Collection $refs;

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
        $this->refs = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function generateTag(): string
    {
        return 'Agent_' . $this->getId();
    }

    /** Accesseur en lecteur de l'identification (importer de la base source) ********************/

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function getNomUsuel(): ?string
    {
        return $this->nomUsuel;
    }

    public function getNomFamille(): ?string
    {
        return $this->nomFamille;
    }

    public function getSexe(): ?string
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

    public function getHarpId(): ?int
    {
        return $this->harpId;
    }


    public function isContratLong(): bool
    {
        return $this->tContratLong === 'O';
    }

    /** @return AgentRef[] */
    public function getRefs(): array
    {
        return $this->refs->toArray();
    }

    /** Collections importées *****************************************************************************************/

    /** @return AgentAffectation[] */
    public function getAffectations(?DateTime $date = null, bool $histo = false): array
    {
        $affectations = $this->affectations->toArray();
        if ($histo === false) $affectations = array_filter($affectations, function (AgentAffectation $ae) {
            return !$ae->isDeleted();
        });
        if ($date !== null) $affectations = array_filter($affectations, function (AgentAffectation $ae) use ($date) {
            return ($ae->estEnCours($date));
        });

        usort($affectations, function (AgentAffectation $a, AgentAffectation $b) {
            return $a->getDateDebut() <=> $b->getDateDebut();
        });
        return $affectations;
    }

    /** @return AgentAffectation[] */
    public function getAffectationsActifs(?DateTime $date = null, ?array $structures = null): array
    {
        if ($date === null) $date = (new DateTime());
        $affectations = $this->getAffectations($date);
        if ($structures !== null) $affectations = array_filter($affectations, function (AgentAffectation $a) use ($structures) {
            return in_array($a->getStructure(), $structures);
        });
        return $affectations;
    }

    //TODO A reecrire car peut être multiple ...
    public function getAffectationPrincipale(?DateTime $date = null): ?AgentAffectation
    {
        if ($date === null) {
            $date = new DateTime();
        }
        foreach ($this->getAffectations() as $affectation) {
            if ($affectation->isPrincipale()
                and ($affectation->getDateDebut() === null or $affectation->getDateDebut() <= $date)
                and ($affectation->getDateFin() === null or $affectation->getDateFin() >= $date)
            ) {
                return $affectation;
            }
        }
        return null;
    }

    /** @return AgentEchelon[] */
    public function getEchelons(?DateTime $date = null, bool $histo = false): array
    {
        $echelons = $this->echelons->toArray();
        if ($histo === false) $echelons = array_filter($echelons, function (AgentEchelon $ae) {
            return !$ae->isDeleted();
        });
        if ($date !== null) $echelons = array_filter($echelons, function (AgentEchelon $ae) use ($date) {
            return ($ae->estEnCours($date));
        });

        usort($echelons, function (AgentEchelon $a, AgentEchelon $b) {
            return $a->getDateDebut() <=> $b->getDateDebut();
        });
        return $echelons;
    }

    /** @return AgentEchelon[] */
    public function getEchelonsActifs(?DateTime $date = null): array
    {
        if ($date === null) $date = (new DateTime());
        return $this->getEchelons($date);
    }

    /** @return AgentGrade[] */
    public function getGrades(?DateTime $date = null, bool $histo = false): array
    {
        $grades = $this->grades->toArray();
        if ($histo === false) $grades = array_filter($grades, function (AgentGrade $ag) {
            return !$ag->isDeleted();
        });
        if ($date !== null) $grades = array_filter($grades, function (AgentGrade $ag) use ($date) {
            return ($ag->estEnCours($date));
        });

        usort($grades, function (AgentGrade $a, AgentGrade $b) {
            return $a->getDateDebut() <=> $b->getDateDebut();
        });
        return $grades;
    }

    /** @return AgentGrade[] */
    public function getGradesActifs(?DateTime $date = null): array
    {
        if ($date === null) $date = (new DateTime());
        return $this->getGrades($date);
    }

    /** @return AgentQuotite[] */
    public function getQuotites(?DateTime $date = null, bool $histo = false): array
    {
        $quotites = $this->quotites->toArray();
        if ($histo === false) $quotites = array_filter($quotites, function (AgentQuotite $q) {
            return !$q->isDeleted();
        });
        if ($date !== null) $quotites = array_filter($quotites, function (AgentQuotite $q) use ($date) {
            return ($q->estEnCours($date));
        });

        usort($quotites, function (AgentQuotite $a, AgentQuotite $b) {
            return $a->getDateDebut() <=> $b->getDateDebut();
        });
        return $quotites;
    }

    /** @return AgentQuotite[] */
    public function getQuotitesActives(?DateTime $date = null): array
    {
        if ($date === null) $date = (new DateTime());
        return $this->getQuotites($date);
    }

    /** @return AgentStatut[] */
    public function getStatuts(?DateTime $date = null, bool $histo = false): array
    {
        $statuts = $this->statuts->toArray();
        if ($histo === false) $statuts = array_filter($statuts, function (AgentStatut $as) {
            return (!$as->isDeleted());
        });
        if ($date !== null) $statuts = array_filter($statuts, function (AgentStatut $as) use ($date) {
            return ($as->estEnCours($date));
        });

        usort($statuts, function (AgentStatut $a, AgentStatut $b) {
            return $a->getDateDebut() <=> $b->getDateDebut();
        });
        return $statuts;
    }

    /** @return AgentStatut[] */
    public function getStatutsActifs(?DateTime $date = null, ?array $structures = null): array
    {
        if ($date === null) $date = (new DateTime());
        $statuts = $this->getStatuts($date);
        if ($structures !== null) $statuts = array_filter($statuts, function (AgentStatut $a) use ($structures) {
            return in_array($a->getStructure(), $structures);
        });
        return $statuts;
    }

    /** @return AgentGrade[] */
    public function getEmploiTypesActifs(?DateTime $date = null, ?array $structures = null): array
    {
        if ($date === null) $date = (new DateTime());
        $grades = $this->getGrades($date);
        if ($structures !== null) $grades = array_filter($grades, function (AgentGrade $a) use ($structures) {
            return in_array($a->getStructure(), $structures);
        });
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

        return Agent::isTermoinsOk($temoins, $count);
    }

    public function isValideStatut(?Parametre $parametre, ?DateTime $date = null, ?array $structures = null, bool $emptyResult = false): bool
    {
        if ($parametre === null) return true;
        $temoins = $parametre->getTemoins();

        $count = [];
        $statuts = $this->getStatutsActifs($date);
        if (empty($statuts)) return $emptyResult;

        foreach ($statuts as $statut) {
            foreach (AgentStatut::TEMOINS as $temoin) {
                if ($statut->getTemoin($temoin)) {
                    $count[$temoin] = true;
                }
            }
        }

        return Agent::isTermoinsOk($temoins, $count);
    }

    public function isValideEmploiType(?Parametre $parametre, ?DateTime $date = null, ?array $structures = null, bool $emptyResult = false): bool
    {
        if ($parametre->getValeur() === null) return $emptyResult;
        $temoins = $parametre->getTemoins();

        $count = [];
        $grades = $this->getEmploiTypesActifs($date);
        if (empty($grades)) return $emptyResult;
        foreach ($grades as $grade) {
            if ($grade->getEmploiType()) $count[$grade->getEmploiType()->getCode()] = true;
        }

        $res = Agent::isTermoinsOk($temoins, $count);
        return $res;
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

        return Agent::isTermoinsOk($temoins, $count);
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

        return Agent::isTermoinsOk($temoins, $count);
    }

    public static function isTermoinsOk(array $temoins, array $count): bool
    {
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

    public function getUtilisateur(): ?AbstractUser
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?AbstractUser $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
    }


    public function getDenomination(bool $prenomFirst = false, bool $nomCap = true, bool $nomBold = false): ?string
    {
        $prenom = $this->getPrenom();
        $prenom = str_replace("É", "é", $prenom);
        $prenom = str_replace("È", "è", $prenom);
        $nom = (($this->getNomUsuel()) ?? '<em>' . $this->getNomFamille() . '</em>');
        if ($nomCap) $nom = strtoupper($nom);
        if ($nomBold) $nom = "<strong>" . $nom . "</strong>";
        if ($prenomFirst) return ucwords(strtolower($prenom), "- ") . ' ' . $nom;
        return $nom . ' ' . ucwords(strtolower($prenom), "- ");

    }

    public function toString(): string
    {
        return $this->getDenomination();
    }


    /** STRUCTURE *****************************************************************************************************/

    /**
     * @param DateTime|null $date
     * @return Structure[]
     */
    public function getStructures(?DateTime $date = null): array
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

    public function isForceExclus(Campagne $campagne): bool
    {
        /** @var AgentForceSansObligation $forcage */
        foreach ($this->forcesSansObligation as $forcage) {
            if ($forcage->estNonHistorise() && $forcage->getCampagne() === $campagne && $forcage->getType() === AgentForceSansObligation::FORCE_EXCLUS) return true;
        }
        return false;
    }


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
    public function getFichePosteActive(bool $incomplement = false): ?FichePoste
    {
        $ficheposte = null;
        foreach ($this->getFiches() as $fiche) {
            if ($fiche->estNonHistorise()
                and ($incomplement or $fiche->isComplete())
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
    public function getFiches(): array
    {
        return $this->fiches->toArray();
    }

    /**
     * @return Fichier[]
     */
    public function getFichiers(): array
    {
        return $this->fichiers->toArray();
    }

    /**
     * @param Fichier $fichier
     * @return Agent
     */
    public function addFichier(Fichier $fichier): Agent
    {
        $this->fichiers->add($fichier);
        return $this;
    }

    /**
     * @param Fichier $fichier
     * @return Agent
     */
    public function removeFichier(Fichier $fichier): Agent
    {
        $this->fichiers->removeElement($fichier);
        return $this;
    }

    /**
     * @param string $nature
     * @return Fichier[]
     */
    public function fetchFiles(string $nature): array
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
    public function getEntretiensProfessionnels(): array
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
    public function getEntretienProfessionnelByCampagne(Campagne $campagne): ?EntretienProfessionnel
    {
        /** @var EntretienProfessionnel $entretien */
        foreach ($this->entretiens as $entretien) {
            if ($entretien->getCampagne() === $campagne) return $entretien;
        }
        return null;
    }

    /**  MISSIONS SPECIFIQUES *****************************************************************************************/

    /** @return AgentMissionSpecifique[] */
    public function getMissionsSpecifiques(): array
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
    public function hasEntretienEnCours(): bool
    {
        $now = (new DateTime());
        /** @var EntretienProfessionnel $entretien */
        foreach ($this->entretiens as $entretien) {
            if ($entretien->estNonHistorise()) {
                try {
                    $date_min = DateTime::createFromFormat('d/m/Y', $entretien->getDateEntretien()->format('d/m/y'));
                    $date_min = $date_min->sub(new DateInterval('P15D'));
                    $date_max = DateTime::createFromFormat('d/m/Y', $entretien->getDateEntretien()->format('d/m/y'));
                } catch (Exception $e) {
                    throw new RuntimeException("Agent::hasEntretien : Une erreur est survenue lors du calcul de la date", 0, $e);
                }
                if ($now >= $date_min and $now <= $date_max) return true;
            }
        }
        return false;
    }

    public function getNiveauEnveloppe(): ?NiveauEnveloppe
    {
        $inferieure = null;
        $superieure = null;

        $grades = $this->getGradesActifs();
        foreach ($grades as $grade) {
            $level = $grade->getCorps()->getNiveaux();

            $_inferieure = ($level !== null) ? $level->getBorneInferieure() : null;
            $_superieure = ($level !== null) ? $level->getBorneSuperieure() : null;

            if ($inferieure === null or ($_inferieure and $_inferieure->getNiveau() < $inferieure->getNiveau())) $inferieure = $_inferieure;
            if ($superieure === null or ($_superieure and $_superieure->getNiveau() > $superieure->getNiveau())) $superieure = $_superieure;
        }

        if ($inferieure === null or $superieure === null) return null;

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
        $result = array_filter($result, function (AgentAutorite $a) {
            return !$a->isDeleted();
        });
        $result = array_filter($result, function (AgentAutorite $a) {
            return $a->estEnCours();
        });
        $result = array_filter($result, function (AgentAutorite $a) {
            return (
                ($a->getSourceId() === 'EMC2' and $a->estNonHistorise())
                or
                ($a->getSourceId() !== "EMC2")
            );
        }
        );
        if ($histo === false) {
            $result = array_filter($result, function (AgentAutorite $a) {
                return $a->estNonHistorise();
            });
        }
        return $result;
    }

    /** @return AgentSuperieur[] */
    public function getSuperieurs(bool $histo = false): array
    {
        /** @var AgentSuperieur[] $result */
        $result = $this->superieurs->toArray();
        $result = array_filter($result, function (AgentSuperieur $a) {
            return !$a->isDeleted();
        });
        $result = array_filter($result, function (AgentSuperieur $a) {
            return $a->estEnCours();
        });
        $result = array_filter($result, function (AgentSuperieur $a) {
            return (
                ($a->getSourceId() === 'EMC2' and $a->estNonHistorise())
                or
                ($a->getSourceId() !== "EMC2")
            );
        }
        );
        if ($histo === false) {
            $result = array_filter($result, function (AgentSuperieur $a) {
                return $a->estNonHistorise();
            });
        }
        return $result;
    }

    /** STRUCTURE AGENT FORCE *****************************************************************************************/

    /**
     * @param bool $keepHisto
     * @return StructureAgentForce[]
     */
    public function getStructuresForcees(bool $keepHisto = false): array
    {
        $structureAgentForces = $this->structuresForcees->toArray();
        if (!$keepHisto) {
            $structureAgentForces = array_filter($structureAgentForces, function (StructureAgentForce $a) {
                return $a->estNonHistorise();
            });
        }
        return $structureAgentForces;
    }

    /** Competence */
    public function getCompetenceDictionnaireComplet(): array
    {
        $dictionnaire = $this->getCompetenceDictionnaire();
        return $dictionnaire;
    }

    public function getApplicationDictionnaireComplet(): array
    {
        $dictionnaire = $this->getApplicationDictionnaire();
        return $dictionnaire;
    }

    /**
     * @return FichePoste|null
     */
    public function getFichePosteBest(): ?FichePoste
    {
        $best = null;
        /** @var FichePoste $fiche */
        foreach ($this->fiches as $fiche) {
            if ($fiche->isEnCours() and $fiche->estNonHistorise()) {
                if ($fiche->isEtatActif(FichePosteEtats::ETAT_CODE_SIGNEE)) $best = $fiche;
                if ($fiche->isEtatActif(FichePosteEtats::ETAT_CODE_OK) && ($best === NULL or !$best->isEtatActif(FichePosteEtats::ETAT_CODE_SIGNEE))) $best = $fiche;
                if ($fiche->isEtatActif(FichePosteEtats::ETAT_CODE_REDACTION) && ($best === NULL or (!$best->isEtatActif(FichePosteEtats::ETAT_CODE_SIGNEE)) && !$best->isEtatActif(FichePosteEtats::ETAT_CODE_OK))) $best = $fiche;
                if ($fiche->isEtatActif(FichePosteEtats::ETAT_CODE_MASQUEE) && ($best === NULL)) $best = $fiche;
            }
        }
        return $best;
    }

    /** @return Fichier[] */
    public function getFichiersByCode(string $code): array
    {
        $result = [];
        foreach ($this->getFichiers() as $fichier) {
            if ($fichier->getNature()->getCode() === $code) $result[] = $fichier;
        }
        return $result;
    }

    public function getStatutToString(?DateTime $date = null): string
    {
        $result = "";

        $statuts = $this->getStatutsActifs($date);
        $isTitulaire = false;
        foreach ($statuts as $statut) {
            if ($statut->isTitulaire()) {
                $isTitulaire = true;
                break;
            }
        }
        if ($isTitulaire) $result .= "Titulaire ";
        $isCDI = false;
        foreach ($statuts as $statut) {
            if ($statut->isCdi()) {
                $isCDI = true;
                break;
            }
        }
        if ($isCDI) $result .= "C.D.I. ";
        $isCDD = false;
        foreach ($statuts as $statut) {
            if ($statut->isCdd()) {
                $isCDD = true;
                break;
            }
        }
        if ($isCDD) $result .= "C.D.D. ";

        return $result;
    }

    public function isAdministratif(?DateTime $date = null): bool
    {
        $statuts = $this->getStatutsActifs($date);
        foreach ($statuts as $statut) if ($statut->isAdministratif()) return true;
        return false;
    }

    public function hasAffectationPrincipale(?Structure $structure): bool
    {
        $affectationPrincipale = $this->getAffectationPrincipale();
        //todo checkbien l'inclusion
        if ($affectationPrincipale and $affectationPrincipale->getStructure() === $structure) return true;
        return false;
    }

    public function hasAffectationActive(?DateTime $dateDebut = null, ?DateTime $dateFin = null): bool
    {
        $affectations = $this->getAffectations();
        foreach ($affectations as $affectation) {
            $dateDebut = $dateDebut??$affectation->getDateDebut();
            $dateFin = $dateFin??$affectation->getDateFin()??$affectation->getDateDebut();
            if (max($affectation->getDateDebut(), $dateDebut <= min($affectation->getDateFin(), $dateFin))) return true;
        }
        return false;
    }

    public function hasCorps(?Corps $corps): bool
    {
        $gradesActifs = $this->getGradesActifs();
        if ($gradesActifs and !empty($gradesActifs)) {
            foreach ($gradesActifs as $gradeActif) {
                if ($gradeActif->getCorps() === $corps) return true;
            }
        }
        return false;
    }

    public function hasGrade(?Grade $grade): bool
    {
        $gradesActifs = $this->getGradesActifs();
        if ($gradesActifs and !empty($gradesActifs)) {
            foreach ($gradesActifs as $gradeActif) {
                if ($gradeActif->getGrade() === $grade) return true;
            }
        }
        return false;
    }


}