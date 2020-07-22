<?php

namespace Application\Entity\Db;

use Application\Service\Agent\AgentServiceAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Fichier\Entity\Db\Fichier;
use UnicaenApp\Exception\RuntimeException;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class Agent implements ResourceInterface
{
    use ImportableAwareTrait;
    use AgentServiceAwareTrait;
    use DateTimeAwareTrait;

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
    /** @var User */
    private $utilisateur;
    /** @var int */
    private $harpId;
    /** @var int */
    private $quotite;

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
    /** @var ArrayCollection (AgentApplication) */
    private $applications;
    /** @var ArrayCollection (AgentCompetence) */
    private $competences;
    /** @var ArrayCollection (AgentFormation) */
    private $formations;


    public function __construct()
    {
        $this->fiches = new ArrayCollection();
        $this->statuts = new ArrayCollection();
        $this->missionsSpecifiques = new ArrayCollection();
        $this->fichiers = new ArrayCollection();
        $this->competences = new ArrayCollection();
        $this->grades = new ArrayCollection();
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
     * @return string
     */
    public function getDenomination()
    {
        return ucwords(strtolower($this->getPrenom()), "-") . ' ' . $this->getNomUsuel();

    }

    /**
     * @return AgentAffectation[]
     */
    public function getAffectations()
    {
        return $this->affectations->toArray();
    }

    /**
     * @return AgentAffectation
     */
    public function getAffectationPrincipale()
    {
        $structure = null;
        /** @var AgentAffectation $affectation */
        foreach ($this->affectations as $affectation) {
            if ($affectation->isPrincipale() and $affectation->isActive()) {
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
        foreach ($this->affectations as $affectation) {
            if ($affectation->isActive()) $affectations[] = $affectation;
        }
        return $affectations;
    }

    /**
     * @return AgentStatut[]
     */
    public function getStatuts()
    {
        return $this->statuts->toArray();
    }

    /**
     * @return AgentStatut[]
     */
    public function getStatutsActifs()
    {
        $now = $this->getDateTime();
        $statuts = [];
        /** @var AgentStatut $statut */
        foreach ($this->statuts as $statut) {
            if ($statut->getFin() === null or $statut->getFin() > $now) $statuts[] = $statut;
        }
        return $statuts;
    }

    /** @return AgentGrade[] */
    public function getGrades()
    {
        return $this->grades->toArray();
    }

    /**
     * @return AgentGrade[]
     */
    public function getGradesActifs()
    {
        $now = $this->getDateTime();
        $grades = [];
        /** @var AgentGrade $grade */
        foreach ($this->grades as $grade) {
            if ($grade->getDateFin() === null or $grade->getDateFin() > $now) $grades[] = $grade;
        }
        return $grades;
    }

    /**
     * @return integer
     */
    public function getHarpId()
    {
        return $this->harpId;
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
     * @return int
     */
    public function getQuotite()
    {
        return $this->quotite;
    }

    /**
     * @param int $quotite
     * @return Agent
     */
    public function setQuotite($quotite)
    {
        $this->quotite = $quotite;
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
     * @param FichePoste $fiche
     * @return Agent
     */
    public function setFiche($fiche)
    {
        $this->fiche = $fiche;
        return $this;
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
    public function addFichier($fichier)
    {
        $this->fichiers->add($fichier);
        return $this;
    }

    /**
     * @param Fichier $fichier
     * @return Agent
     */
    public function removeFichier($fichier)
    {
        $this->fichiers->removeElement($fichier);
        return $this;
    }

    /**
     * @param string $nature
     * @return Fichier[]
     */
    public function fetchFiles($nature)
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

    /** APPLICATIONS, COMPETENCES ET FORMATIONS ***********************************************************************/

    /**
     * @param bool $include_historisees
     * @return AgentApplication[]
     */
    public function getApplications($include_historisees = false)
    {
        $applications = [];
        /** @var AgentApplication $application */
        foreach ($this->applications as $application) {
            if ($include_historisees OR $application->estNonHistorise()) $applications[] = $application;
        }
        return $applications;
    }

    /**
     * @param bool $include_historisees
     * @return AgentCompetence[]
     */
    public function getCompetences($include_historisees = false)
    {
        $competences = [];
        /** @var AgentCompetence $competence */
        foreach ($this->competences as $competence) {
            if ($include_historisees OR $competence->estNonHistorise()) $competences[] = $competence;
        }
        return $competences;
    }

    /**
     * @param bool $include_historisees
     * @return AgentFormation[]
     */
    public function getFormations($include_historisees = false)
    {
        $formations = [];
        /** @var AgentCompetence $formation */
        foreach ($this->formations as $formation) {
            if ($include_historisees OR $formation->estNonHistorise()) $formations[] = $formation;
        }
        return $formations;
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

    /**
     * @param Formation $formation
     * @return AgentFormation
     */
    public function hasFormation(Formation $formation)
    {
        /** @var AgentFormation $aformation */
        foreach ($this->formations as $aformation) {
            if ($aformation->getFormation() === $formation) return $aformation;
        }
        return null;
    }

    /**
     * @param Formation $formation
     * @return AgentFormation
     */
    public function hasValidatedFormation(Formation $formation)
    {
        /** @var AgentFormation $aformation */
        foreach ($this->formations as $aformation) {
            if ($aformation->getFormation() === $formation AND $aformation->estValide()) return $aformation;
        }
        return null;
    }

    public function hasEntretienEnCours() {
        $now = $this->getDateTime();

        /** @var EntretienProfessionnel $entretien */
        foreach ($this->entretiens as $entretien) {
            if ($entretien->getDateEntretien() > $now
                AND ($entretien->getValidationResponsable() === null OR $entretien->getValidationResponsable()->estNonHistorise())
                AND $entretien->estNonHistorise()) return true;
        }

        return false;
    }
}