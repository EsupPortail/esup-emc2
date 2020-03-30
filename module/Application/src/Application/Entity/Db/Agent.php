<?php

namespace Application\Entity\Db;

use Application\Service\Agent\AgentServiceAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Fichier\Entity\Db\Fichier;
use UnicaenUtilisateur\Entity\Db\User;

class Agent {
    use ImportableAwareTrait;
    use AgentServiceAwareTrait;

    /** @var string */
    private $id;
    /** @var string */
    private $sourceName;
    /** @var integer */
    private $sourceId;
    /** @var string */
    private $prenom;
    /** @var string */
    private $nomUsuel;
    /** @var User */
    private $utilisateur;
    /** @var int */
    private $quotite;

    /** @var ArrayCollection (FichePoste) */
    private $fiches;
    /** @var ArrayCollection (AgentStatut)*/
    private $statuts;
    /** @var ArrayCollection (AgentMissionSpecifique) */
    private $missionsSpecifiques;
    /** @var ArrayCollection (Fichier) */
    private $fichiers;
    /** @var ArrayCollection (AgentCompetence) */
    private $competences;
    /** @var ArrayCollection (AgentGrade) */
    private $grades;

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
    public function getSourceName()
    {
        return $this->sourceName;
    }

    /**
     * @return int
     */
    public function getSourceId()
    {
        return $this->sourceId;
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
        return ucwords(strtolower($this->getPrenom()), "-").' '.$this->getNomUsuel();

    }

    /**
     * @return AgentStatut[]
     */
    public function getStatuts() {
        return $this->statuts->toArray();
    }

    /** @return AgentGrade[] */
    public function getGrades() {
        return $this->grades->toArray();
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
    public function setUtilisateur($utilisateur)
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
        $fichiers = array_filter($fichiers, function (Fichier $f) use ($nature) { return ($f->getHistoDestruction() === null && $f->getNature()->getCode() === $nature);});

        return $fichiers;
    }

    /** @return AgentMissionSpecifique[] */
    public function getMissionsSpecifiques() {
        return $this->missionsSpecifiques->toArray();
    }

    /** @return AgentCompetence[] */
    public function getCompetences() {
        return $this->competences->toArray();
    }

    /**
     * @param AgentCompetence $competence
     * @return Agent
     */
    public function addCompetence($competence) {
        $this->competences->add($competence);
        return $this;
    }

    /**
     * @param AgentCompetence $competence
     * @return Agent
     */
    public function removeCompetence($competence) {
        $this->competences->removeElement($competence);
        return $this;
    }

    /**
     * @param AgentCompetence $competence
     * @return boolean
     */
    public function hasCompetence($competence) {
        return $this->competences->contains($competence);
    }
}