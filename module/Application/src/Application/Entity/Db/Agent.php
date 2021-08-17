<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Application\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Application\Entity\Db\MacroContent\AgentMacroTrait;
use Application\Entity\Db\Traits\HasApplicationCollectionTrait;
use Application\Entity\Db\Traits\HasCompetenceCollectionTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use Fichier\Entity\Db\Fichier;
use Formation\Entity\Db\Interfaces\HasFormationCollectionInterface;
use Formation\Entity\Db\Traits\HasFormationCollectionTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class Agent implements
    ResourceInterface,
    HasApplicationCollectionInterface, HasCompetenceCollectionInterface, HasFormationCollectionInterface
{
    use ImportableAwareTrait;
    use AgentServiceAwareTrait;
    use DateTimeAwareTrait;
    use HasApplicationCollectionTrait;  use HasCompetenceCollectionTrait;  use HasFormationCollectionTrait;
    use AgentMacroTrait;

    public function getResourceId()
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
    private $sexe;
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

    /** @var ArrayCollection (AgentQuotite) */
    private $quotites;
    /** @var ArrayCollection (AgentAffectation) */
    private $affectations;
    /** @var ArrayCollection (AgentGrade) */
    private $grades;
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
//    /** @var ArrayCollection (AgentFormation) */
//    private $formations;

    /** @var ArrayCollection (StructureAgentForce) */
    private $structuresForcees;

    public function __construct()
    {
        $this->fiches = new ArrayCollection();
        $this->statuts = new ArrayCollection();
        $this->missionsSpecifiques = new ArrayCollection();
        $this->fichiers = new ArrayCollection();
        $this->grades = new ArrayCollection();
        $this->structuresForcees = new ArrayCollection();
    }

    /** Éléments importés (octopus) : pas besoins de setters **********************************************************/

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @return string
     */
    public function getNomUsuel()
    {
        return $this->nomUsuel;
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
    public function getDenomination()
    {
        return ucwords(strtolower($this->getPrenom()), "-") . ' ' . $this->getNomUsuel();

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
     * @return integer
     */
    public function getHarpId()
    {
        return $this->harpId;
    }


    /** AFFECTATIONS **************************************************************************************************/

    public function isIn(Structure $structure)
    {
        return true;
    }

    /**
     * @param DateTime|null $date
     * @return AgentAffectation[]
     */
    public function getAffectations(?DateTime $date = null)
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
    public function getAffectationPrincipale()
    {
        $structure = null;
        /** @var AgentAffectation $affectation */
        foreach ($this->getAffectations() as $affectation) {
            if ($affectation->isPrincipale() and $affectation->estEnCours()) {
                return $affectation;
            }
        }
        return null;
    }

    /**
     * @return AgentAffectation[]
     */
    public function getAffectationsActifs()
    {
        $date = $this->getDateTime();

        $affectations = [];
        /** @var AgentAffectation $affectation */
        foreach ($this->getAffectations() as $affectation) {
            if ($affectation->estEnCours($date)) $affectations[] = $affectation;
        }
        return $affectations;
    }

    /** STATUTS *******************************************************************************************************/

    /**
     * @return AgentStatut[]
     */
    public function getStatuts()
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
    public function getStatutsActifs()
    {
        $now = $this->getDateTime();
        $statuts = [];
        /** @var AgentStatut $statut */
        foreach ($this->getStatuts() as $statut) {
            if ($statut->estEnCours($now)) $statuts[] = $statut;
        }
        return $statuts;
    }

    /** GRADES ********************************************************************************************************/

    /** @return AgentGrade[] */
    public function getGrades()
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
    public function getGradesActifs()
    {
        $grades = [];
        /** @var AgentGrade $grade */
        foreach ($this->getGrades() as $grade) {
            if ($grade->estEnCours()) $grades[] = $grade;
        }
        return $grades;
    }

    /** STRUCTURES  ***************************************************************************************************/

    /**
     * Les structures d'un agents correspondent à la liste des structures de ses affectations actives.
     * /!\ une structure peut être répétée
     * @return Structure[]
     */
    public function getStructures() : array
    {
        $structures = [];
        $affectations = $this->getAffectationsActifs();
        foreach ($affectations as $affectation) {
            $structure = $affectation->getStructure();
            $structures[$structure->getId()] = $structure;
        }
        return $structures;
    }

    /** Éléments non importés *****************************************************************************************/

    /**
     * @return User
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * @param User $utilisateur
     * @return Agent
     */
    public function setUtilisateur(User $utilisateur)
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    /**
     * @return FichePoste[]
     */
    public function getFiches()
    {
        return $this->fiches->toArray();
    }

    /**
     * @return Fichier[]
     */
    public function getFichiers()
    {
        return $this->fichiers->toArray();
    }

    /**
     * @param Fichier $fichier
     * @return Agent
     */
    public function addFichier(Fichier $fichier)
    {
        $this->fichiers->add($fichier);
        return $this;
    }

    /**
     * @param Fichier $fichier
     * @return Agent
     */
    public function removeFichier(Fichier $fichier)
    {
        $this->fichiers->removeElement($fichier);
        return $this;
    }

    /**
     * @param string $nature
     * @return Fichier[]
     */
    public function fetchFiles(string $nature)
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
    public function getEntretiensProfessionnels()
    {
        $entretiens = [];
        /** @var EntretienProfessionnel $entretien */
        foreach ($this->entretiens as $entretien) {
            if ($entretien->estNonHistorise()) $entretiens[] = $entretien;
        }
        return $entretiens;
    }

    /**  MISSIONS SPECIFIQUES *****************************************************************************************/

    /**
     * @return MissionSpecifique[]
     */
    /** @return AgentMissionSpecifique[] */
    public function getMissionsSpecifiques()
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

    /**
     * @return FichePoste
     */
    public function getFichePosteActif()
    {
        $now = $this->getDateTime();
        $fiches = [];
        /** @var FichePoste $fiche */
        foreach ($this->fiches as $fiche) {
            if ($fiche->getHistoCreation() <= $now and $fiche->estNonHistorise($now)) {
                $fiches[] = $fiche;
            }
        }
        if (count($fiches) > 1) throw new RuntimeException("Un agent a plus d'une fiche de poste active", 0, null);
        if (empty($fiches)) return null;
        return $fiches[0];
    }

    /**
     * @return Poste
     */
    public function getPosteActif()
    {
        $fiche = $this->getFichePosteActif();
        return ($fiche) ? $fiche->getPoste() : null;
    }

    /** Entretien dans moins de 15 jours */
    public function hasEntretienEnCours() {
        $now = $this->getDateTime();
        /** @var EntretienProfessionnel $entretien */
        foreach ($this->entretiens as $entretien) {
            if ($entretien->estNonHistorise()) {
                $date_min = DateTime::createFromFormat('d/m/Y', $entretien->getDateEntretien()->format('d/m/y'));
                $date_min = $date_min->sub(new \DateInterval('P15D'));
                $date_max = DateTime::createFromFormat('d/m/Y', $entretien->getDateEntretien()->format('d/m/y'));
                if ($now >= $date_min and $now <= $date_max) return true;
            }
        }
        return false;
    }

    /**
     * @return Niveau
     */
    public function getMeilleurNiveau()
    {
        $niveau = 999;
        $grades = $this->getGradesActifs();
        foreach ($grades as $grade) {
            $level = $grade->getCorps()->getNiveau();
            if ($level !== null and $level <= $niveau) $niveau = $level;
        }
        return ($niveau !== 999)?$niveau:null;
    }

    /** QUOTITES *****************************************************************************************/

    /**
     * @return AgentQuotite[]
     */
    public function getQuotites() : array {
        $quotites = $this->quotites->toArray();
        array_filter($quotites, function (AgentQuotite $q) { return $q->getImportationHistorisation(); });
        usort($quotites, function(AgentQuotite $a, AgentQuotite $b) { return $a->getDebut() > $b->getDebut();});
        return $quotites;
    }

    /**
     * @param DateTime|null $date
     * @return AgentQuotite|null
     */
    public function getQuotiteCourante(?DateTime $date = null) : ?AgentQuotite {
        /** @var AgentQuotite $quotite */
        foreach ($this->quotites as $quotite) {
            if ($quotite->getImportationHistorisation() === null AND $quotite->isEnCours($date)) return $quotite;
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
    public function getCompetenceDictionnaireComplet()
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

    public function getApplicationDictionnaireComplet()
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
}