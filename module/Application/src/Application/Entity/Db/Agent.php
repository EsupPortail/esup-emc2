<?php

namespace Application\Entity\Db;

use Carriere\Entity\Db\Niveau;
use Carriere\Entity\Db\NiveauEnveloppe;
use Element\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Element\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Application\Entity\Db\Interfaces\HasComplementsInterface;
use Application\Entity\Db\MacroContent\AgentMacroTrait;
use Application\Entity\Db\Traits\DbImportableAwareTrait;
use Element\Entity\Db\Traits\HasApplicationCollectionTrait;
use Element\Entity\Db\Traits\HasCompetenceCollectionTrait;
use Application\Entity\Db\Traits\HasComplementsTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use Exception;
use Fichier\Entity\Db\Fichier;
use Formation\Entity\Db\Interfaces\HasFormationCollectionInterface;
use Formation\Entity\Db\Traits\HasFormationCollectionTrait;
use Structure\Entity\Db\Structure;
use Structure\Entity\Db\StructureAgentForce;
use UnicaenUtilisateur\Entity\Db\User;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class Agent implements
    ResourceInterface,
    HasApplicationCollectionInterface, HasCompetenceCollectionInterface, HasFormationCollectionInterface,
    HasComplementsInterface
{
    use DbImportableAwareTrait;
    use AgentServiceAwareTrait;
    use HasApplicationCollectionTrait;  use HasCompetenceCollectionTrait;  use HasFormationCollectionTrait;
    use HasComplementsTrait;
    use AgentMacroTrait;

    const ROLE_AGENT         = 'Agent';
    const ROLE_SUPERIEURE    = 'Supérieur·e hiérarchique direct·e';
    const ROLE_AUTORITE      = 'Autorité hiérarchique';

    public function getResourceId() : string
    {
        return 'Agent';
    }

    /** @var string */
    private $id;
    /** @var string */
    private $prenom;
    /** @var string */
    private $nomUsuel;
    /** @var string */
    private $nomFamille;
    /** @var string */
    private $sexe;
    /** @var DateTime */
    private $dateNaissance;
    /** @var User */
    private $utilisateur;
    /** @var int */
    private $harpId;
    /** @var string */
    private $login;
    /** @var string */
    private $email;
    /** @var DateTime */
    private $delete;
    /** @var string */
    private $tContratLong;

    /** @var ArrayCollection (AgentQuotite) */
    private $quotites;
    /** @var ArrayCollection (AgentAffectation) */
    private $affectations;
    /** @var ArrayCollection (AgentGrade) */
    private $grades;
    /** @var ArrayCollection (AgentEchelon) */
    private $echelons;
    /** @var ArrayCollection (AgentStatut) */
    private $statuts;

    /** @var ArrayCollection (FichePoste) */
    private $fiches;
    /** @var ArrayCollection (EntretienProfessionnel) */
    private $entretiens;

    /** @var ArrayCollection (AgentMissionSpecifique) */
    private $missionsSpecifiques;
    /** @var ArrayCollection (Fichier) */
    private $fichiers;

    /** @var ArrayCollection (StructureAgentForce) */
    private $structuresForcees;

    public function __construct()
    {
        $this->fiches = new ArrayCollection();
        $this->statuts = new ArrayCollection();
        $this->missionsSpecifiques = new ArrayCollection();
        $this->fichiers = new ArrayCollection();
        $this->echelons = new ArrayCollection();
        $this->grades = new ArrayCollection();
        $this->structuresForcees = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function generateTag()  : string
    {
        return 'Agent_' . $this->getId();
    }

    /** Éléments importés (octopus) : pas besoins de setters **********************************************************/

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPrenom() : ?string
    {
        return $this->prenom;
    }

    /**
     * @return string
     */
    public function getNomUsuel() : ?string
    {
        return $this->nomUsuel;
    }

    /**
     * @return string
     */
    public function getNomFamille() : ?string
    {
        return $this->nomFamille;
    }

    /**
     * @return string|null
     */
    public function getSexe() : ?string
    {
        return $this->sexe;
    }

    /**
     * @param string|null $sexe
     * @return Agent
     */
    public function setSexe(?string $sexe) : Agent
    {
        $this->sexe = $sexe;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDateNaissance(): ?DateTime
    {
        return $this->dateNaissance;
    }

    /**
     * @param DateTime $dateNaissance
     * @return Agent
     */
    public function setDateNaissance(DateTime $dateNaissance): Agent
    {
        $this->dateNaissance = $dateNaissance;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHomme() : bool
    {
        return $this->sexe === 'M';
    }

    /**
     * @return bool
     */
    public function isFemme() : bool
    {
        return $this->sexe === 'F';
    }

    /**
     * @return string
     */
    public function getDenomination() : ?string
    {
        return ucwords(strtolower($this->getPrenom()), "-") . ' ' . $this->getNomUsuel();

    }

    public function toString() : string
    {
        return $this->getDenomination();
    }

    /**
     * @return string|null
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * @param string|null $login
     * @return Agent
     */
    public function setLogin(?string $login): Agent
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return Agent
     */
    public function setEmail(?string $email): Agent
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getHarpId() : ?int
    {
        return $this->harpId;
    }

    /**
     * @return string
     */
    public function getTControatLong() : ?string
    {
        return $this->tContratLong;
    }

    /**
     * @return bool
     */
    public function isContratLong() : bool
    {
        return $this->getTControatLong() === 'O';
    }

    /** AFFECTATIONS **************************************************************************************************/

    /**
     * @param DateTime|null $date
     * @return AgentAffectation[]
     */
    public function getAffectations(?DateTime $date = null) : array
    {
        $affectations = $this->affectations->toArray();
        $affectations = array_filter($affectations, function (AgentAffectation $aa) { return !$aa->isDeleted();});
        if ($date !== null) {
            $affectations = array_filter($affectations, function (AgentAffectation $aa) use ($date) {
                return ($aa->estEnCours($date));
            });
        }

        usort($affectations, function (AgentAffectation $a, AgentAffectation $b) {
            return $a->getDateDebut() < $b->getDateDebut();
        });
        return $affectations;
    }

    /**
     * @return AgentAffectation
     */
    public function getAffectationPrincipale() : ?AgentAffectation
    {
        /** @var AgentAffectation $affectation */
        foreach ($this->getAffectations() as $affectation) {
            if ($affectation->isPrincipale() and $affectation->estEnCours()) {
                return $affectation;
            }
        }
        return null;
    }

    /**
     * @param DateTime|null $date
     * @return AgentAffectation[]
     */
    public function getAffectationsActifs(?DateTime $date = null) : array
    {
        if ($date === null) $date = (new DateTime());

        $affectations = [];
        /** @var AgentAffectation $affectation */
        foreach ($this->getAffectations() as $affectation) {
            if ($affectation->estEnCours($date)) $affectations[] = $affectation;
        }
        return $affectations;
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

    /** STATUTS *******************************************************************************************************/

    /**
     * @return AgentStatut[]
     */
    public function getStatuts() : array
    {
        $statuts = $this->statuts->toArray();
        $statuts = array_filter($statuts, function (AgentStatut $as) { return !$as->isDeleted();});
        usort($statuts, function (AgentStatut $a, AgentStatut $b) {
            return $a->getDateDebut() < $b->getDateDebut();
        });
        return $statuts;
    }

    /**
     * @return AgentStatut[]
     */
    public function getStatutsActifs() : array
    {
        $now = (new DateTime());
        $statuts = [];
        /** @var AgentStatut $statut */
        foreach ($this->getStatuts() as $statut) {
            if ($statut->estEnCours($now)) $statuts[] = $statut;
        }
        return $statuts;
    }

    /** GRADES ********************************************************************************************************/

    /** @return AgentGrade[] */
    public function getGrades() : array
    {
        $grades = $this->grades->toArray();
        $grades = array_filter($grades, function (AgentGrade $ag) { return !$ag->isDeleted();});
        usort($grades, function (AgentGrade $a, AgentGrade $b) {
            return $a->getDateDebut() < $b->getDateDebut();
        });
        return $grades;
    }

    /**
     * @return AgentGrade[]
     */
    public function getGradesActifs() : array
    {
        $grades = [];
        /** @var AgentGrade $grade */
        foreach ($this->getGrades() as $grade) {
            if ($grade->estEnCours()) $grades[] = $grade;
        }
        return $grades;
    }

    /** ECHELONS ********************************************************************************************************/

    /** @return AgentEchelon[] */
    public function getEchelons() : array
    {
        $echelons = $this->echelons->toArray();
        $grades = array_filter($echelons, function (AgentEchelon $ag) { return !$ag->isDeleted();});
        usort($grades, function (AgentEchelon $a, AgentEchelon $b) {
            return $a->getDate() > $b->getDate();
        });
        return $grades;
    }

    /**
     * @return AgentEchelon|null
     */
    public function getEchelonActif() : ?AgentEchelon
    {
        $echelons = $this->getEchelons();
        $echelon = (!empty($echelons))?$echelons[0]:null;
        return $echelon;
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

    /** Éléments non importés *****************************************************************************************/

    /**
     * @return User|null
     */
    public function getUtilisateur() : ?User
    {
        return $this->utilisateur;
    }

    /**
     * @param User|null $utilisateur
     * @return Agent
     */
    public function setUtilisateur(?User $utilisateur) : Agent
    {
        $this->utilisateur = $utilisateur;
        return $this;
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

    /**
     * @return MissionSpecifique[]
     */
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
            if ($aDebut !== $bDebut) return $aDebut > $bDebut;
            return $aFin > $bFin;
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

    /**
     * @return NiveauEnveloppe
     */
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

    /** QUOTITES *****************************************************************************************/

    /**
     * @return AgentQuotite[]
     */
    public function getQuotites() : array
    {
        $quotites = $this->quotites->toArray();
        array_filter($quotites, function (AgentQuotite $q) { return !$q->isDeleted(); });
        usort($quotites, function(AgentQuotite $a, AgentQuotite $b) { return $a->getDateDebut() > $b->getDateDebut();});
        return $quotites;
    }

    /**
     * @param DateTime|null $date
     * @return AgentQuotite|null
     */
    public function getQuotiteCourante(?DateTime $date = null) : ?AgentQuotite
    {
        /** @var AgentQuotite $quotite */
        foreach ($this->quotites as $quotite) {
            if (!$quotite->isDeleted() AND $quotite->estEnCours($date)) return $quotite;
        }
        return null;
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
     * @param Agent $superieur
     * @return bool
     */
    public function hasSuperieurHierarchique(Agent $superieur) : bool
    {
        foreach ($this->getComplements() as $complement) {
            if ($complement->getType() === Complement::COMPLEMENT_TYPE_RESPONSABLE AND $complement->getComplementId() == $superieur->getId()) return true;
        }
        return false;
    }

    /**
     * @param Agent $autorite
     * @return bool
     */
    public function hasAutoriteHierarchique(Agent $autorite) : bool
    {
        foreach ($this->getComplements() as $complement) {
            if ($complement->getType() === Complement::COMPLEMENT_TYPE_AUTORITE AND $complement->getComplementId() == $autorite->getId()) return true;
        }
        return false;
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
                if ($fiche->getEtat()->getCode() === FichePoste::ETAT_CODE_SIGNEE) $best = $fiche;
                if ($fiche->getEtat()->getCode() === FichePoste::ETAT_CODE_OK AND ($best === NULL OR $best->getEtat()->getCode() !== FichePoste::ETAT_CODE_SIGNEE)) $best = $fiche;
                if ($fiche->getEtat()->getCode() === FichePoste::ETAT_CODE_REDACTION AND ($best === NULL OR ($best->getEtat()->getCode() !== FichePoste::ETAT_CODE_SIGNEE AND $best->getEtat()->getCode() !== FichePoste::ETAT_CODE_OK))) $best = $fiche;
                if ($fiche->getEtat()->getCode() === FichePoste::ETAT_CODE_MASQUEE AND ($best === NULL)) $best = $fiche;
            }
        }
        return $best;
    }

    public function getFichiersByCode(string $code) : array
    {
        $result = [];
        /** @var Fichier $fichier */
        foreach ($this->getFichiers() as $fichier) {
            if ($fichier->getNature()->getCode() === $code) $result[] = $fichier;
        }
        return $result;
    }
}