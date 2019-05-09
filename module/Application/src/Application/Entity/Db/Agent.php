<?php

namespace Application\Entity\Db;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Fichier\Entity\Db\Fichier;
use UnicaenApp\Exception\RuntimeException;
use Utilisateur\Entity\Db\User;

class Agent {

    /** @var integer */
    private $id;
    /** @var string */
    private $nom;
    /** @var string */
    private $prenom;
    /** @var string */
    private $numeroPoste;

    // categorie, corps, et grade (devrait correspondre à des infos en bases pour une meilleures structuration ...

    /** @var DateTime */
    private $dateDebut;
    /** @var DateTime */
    private $dateFin;
    /** @var integer */
    private $quotite;
    /** @var AgentStatus */
    private $status;
    /** @var ArrayCollection (AgentStatut)*/
    private $statuts;
    /** @var Correspondance */
    private $correspondance;
    /** @var Corps */
    private $corps;
    /** @var Grade */
    private $grade;
    /** @var User */
    private $utilisateur;


    /** @var string */
    private $missionsComplementaires;

    /** @var ArrayCollection */
    private $fichiers;

    public function __construct()
    {
        $this->statuts  = new ArrayCollection();
        $this->fichiers = new ArrayCollection();
    }
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
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return Agent
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     * @return Agent
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getDenomination()
    {
        return $this->getPrenom().' '.$this->getNom();

    }
    /**
     * @return DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @param DateTime $dateDebut
     * @return Agent
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * @param DateTime $dateFin
     * @return Agent
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
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
     * @return AgentStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param AgentStatus $status
     * @return Agent
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return AgentStatut[]
     */
    public function getStatuts()
    {
        return $this->statuts->toArray();
    }

    /**
     * @param AgentStatut $statut
     * @return Agent
     */
    public function addStatut($statut)
    {
        $this->statuts->add($statut);
        return $this;
    }

    /**
     * @param AgentStatut $statut
     * @return Agent
     */
    public function removeStatut($statut)
    {
        $this->statuts->removeElement($statut);
        return $this;
    }

    /**
     * @return Correspondance
     */
    public function getCorrespondance()
    {
        return $this->correspondance;
    }

    /**
     * @param Correspondance $correspondance
     * @return Agent
     */
    public function setCorrespondance($correspondance)
    {
        $this->correspondance = $correspondance;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumeroPoste()
    {
        return $this->numeroPoste;
    }

    /**
     * @param string $numeroPoste
     * @return Agent
     */
    public function setNumeroPoste($numeroPoste)
    {
        $this->numeroPoste = $numeroPoste;
        return $this;
    }

    /**
     * @return Corps
     */
    public function getCorps()
    {
        return $this->corps;
    }

    /**
     * @param Corps $corps
     * @return Agent
     */
    public function setCorps($corps)
    {
        $this->corps = $corps;
        return $this;
    }

    /**
     * @return string
     */
    public function getMissionsComplementaires()
    {
        return $this->missionsComplementaires;
    }

    /**
     * @param string $missionsComplementaires
     * @return Agent
     */
    public function setMissionsComplementaires($missionsComplementaires)
    {
        $this->missionsComplementaires = $missionsComplementaires;
        return $this;
    }

    /**
     * @return Grade
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * @param Grade $grade
     * @return Agent
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;
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
     * @param string $natureCode
     * @return Fichier
     */
    public function fetchFile($natureCode)
    {
        $result = $this->fetchFiles($natureCode);
        $nb = count($result);

        if ($nb === 0) return null;
        if ($nb > 1) throw new RuntimeException("Plusieurs fichiers de nature [] trouvés.");

        return current($result);
    }

    /**
     * @param string $natureCode
     * @return Fichier[]
     */
    public function fetchFiles($natureCode)
    {
        $result = [];
        /** @var Fichier $fichier */
        foreach ($this->fichiers as $fichier) {
            if ($fichier->getNature()->getCode() === $natureCode) $result[] = $fichier;
        }
        return $result;
    }

    /**
     * @return AgentStatut[]
     */
    public function getStatutsActifs()
    {
        $statutsActifs = [];
        foreach ($this->statuts as $statut) {
            if ($statut->isActif()) {
                $statutsActifs[] = $statut;
            }
        }

        return $statutsActifs;
    }
}