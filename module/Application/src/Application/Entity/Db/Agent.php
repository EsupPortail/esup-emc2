<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Application\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
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
     * @return AgentAffectation[]
     */
    public function getAffectations()
    {
        $affectations = $this->affectations->toArray();
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
            if ($affectation->isActive($date)) $affectations[] = $affectation;
        }
        return $affectations;
    }

    /**
     * @return AgentStatut[]
     */
    public function getStatuts()
    {
        $status = $this->statuts->toArray();
        usort($statuts, function (AgentStatut $a, AgentStatut $b) {
            return $a->getDebut() < $b->getDebut();
        });
        return $status;
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
        $grades = $this->grades->toArray();
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

    /**
     * @return int
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
    public function getQuotites() {
        $quotites = $this->quotites->toArray();
        array_filter($quotites, function (AgentQuotite $q) { return $q->getImportationHistorisation(); });
        usort($quotites, function(AgentQuotite $a, AgentQuotite $b) { return $a->getDebut() > $b->getDebut();});
        return $quotites;
    }

    /**
     * @param DateTime|null $date
     * @return AgentQuotite|null
     */
    public function getQuotiteCourante(?DateTime $date = null) {
        /** @var AgentQuotite $quotite */
        foreach ($this->quotites as $quotite) {
            if ($quotite->isEnCours($date)) return $quotite;
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
}