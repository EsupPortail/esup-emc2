<?php

namespace Application\Entity\Db;

use DateTime;
use Exception;
use UnicaenApp\Exception\RuntimeException;

class AgentStatut {
    /** @var integer */
    private $id;
    /** @var string */
    private $sourceName;
    /** @var string */
    private $sourceId;
    /** @var string */
    private $idOrigine;
    /** @var Agent */
    private $agent;
    /** @var Structure */
    private $structure;
    /** @var DateTime */
    private $debut;
    /** @var DateTime */
    private $fin;

    /** @var boolean */
    private $titulaire;
    /** @var boolean */
    private $cdi;
    /** @var boolean */
    private $cdd;
    /** @var boolean */
    private $vacataire;
    /** @var boolean */
    private $enseignant;
    /** @var boolean */
    private $administratif;
    /** @var boolean */
    private $chercheur;
    /** @var boolean */
    private $etudiant;
    /** @var boolean */
    private $auditeurLibre;
    /** @var boolean */
    private $doctorant;
    /** @var boolean */
    private $detacheIn;
    /** @var boolean */
    private $detacheOut;
    /** @var boolean */
    private $dispo;
    /** @var boolean */
    private $heberge;
    /** @var boolean */
    private $emerite;
    /** @var boolean */
    private $retraite;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return AgentStatut
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getSourceName()
    {
        return $this->sourceName;
    }

    /**
     * @param string $sourceName
     * @return AgentStatut
     */
    public function setSourceName($sourceName)
    {
        $this->sourceName = $sourceName;
        return $this;
    }

    /**
     * @return string
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * @param string $sourceId
     * @return AgentStatut
     */
    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdOrigine()
    {
        return $this->idOrigine;
    }

    /**
     * @param string $idOrigine
     * @return AgentStatut
     */
    public function setIdOrigine($idOrigine)
    {
        $this->idOrigine = $idOrigine;
        return $this;
    }

    /**
     * @return Agent
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param Agent $agent
     * @return AgentStatut
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * @param Structure $structure
     * @return AgentStatut
     */
    public function setStructure($structure)
    {
        $this->structure = $structure;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDebut()
    {
        return $this->debut;
    }

    /**
     * @param DateTime $debut
     * @return AgentStatut
     */
    public function setDebut($debut)
    {
        $this->debut = $debut;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * @param DateTime $fin
     * @return AgentStatut
     */
    public function setFin($fin)
    {
        $this->fin = $fin;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTitulaire()
    {
        return $this->titulaire;
    }

    /**
     * @param bool $titulaire
     * @return AgentStatut
     */
    public function setTitulaire($titulaire)
    {
        $this->titulaire = $titulaire;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCdi()
    {
        return $this->cdi;
    }

    /**
     * @param bool $cdi
     * @return AgentStatut
     */
    public function setCdi($cdi)
    {
        $this->cdi = $cdi;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCdd()
    {
        return $this->cdd;
    }

    /**
     * @param bool $cdd
     * @return AgentStatut
     */
    public function setCdd($cdd)
    {
        $this->cdd = $cdd;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVacataire()
    {
        return $this->vacataire;
    }

    /**
     * @param bool $vacataire
     * @return AgentStatut
     */
    public function setVacataire($vacataire)
    {
        $this->vacataire = $vacataire;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnseignant()
    {
        return $this->enseignant;
    }

    /**
     * @param bool $enseignant
     * @return AgentStatut
     */
    public function setEnseignant($enseignant)
    {
        $this->enseignant = $enseignant;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAdministratif()
    {
        return $this->administratif;
    }

    /**
     * @param bool $administratif
     * @return AgentStatut
     */
    public function setAdministratif($administratif)
    {
        $this->administratif = $administratif;
        return $this;
    }

    /**
     * @return bool
     */
    public function isChercheur()
    {
        return $this->chercheur;
    }

    /**
     * @param bool $chercheur
     * @return AgentStatut
     */
    public function setChercheur($chercheur)
    {
        $this->chercheur = $chercheur;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEtudiant()
    {
        return $this->etudiant;
    }

    /**
     * @param bool $etudiant
     * @return AgentStatut
     */
    public function setEtudiant($etudiant)
    {
        $this->etudiant = $etudiant;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAuditeurLibre()
    {
        return $this->auditeurLibre;
    }

    /**
     * @param bool $auditeurLibre
     * @return AgentStatut
     */
    public function setAuditeurLibre($auditeurLibre)
    {
        $this->auditeurLibre = $auditeurLibre;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDoctorant()
    {
        return $this->doctorant;
    }

    /**
     * @param bool $doctorant
     * @return AgentStatut
     */
    public function setDoctorant($doctorant)
    {
        $this->doctorant = $doctorant;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDetacheIn()
    {
        return $this->detacheIn;
    }

    /**
     * @param bool $detacheIn
     * @return AgentStatut
     */
    public function setDetacheIn($detacheIn)
    {
        $this->detacheIn = $detacheIn;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDetacheOut()
    {
        return $this->detacheOut;
    }

    /**
     * @param bool $detacheOut
     * @return AgentStatut
     */
    public function setDetacheOut($detacheOut)
    {
        $this->detacheOut = $detacheOut;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isDispo()
    {
        return $this->dispo;
    }

    /**
     * @param boolean $dispo
     * @return AgentStatut
     */
    public function setDispo($dispo)
    {
        $this->dispo = $dispo;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHeberge()
    {
        return $this->heberge;
    }

    /**
     * @param bool $heberge
     * @return AgentStatut
     */
    public function setHeberge($heberge)
    {
        $this->heberge = $heberge;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEmerite()
    {
        return $this->emerite;
    }

    /**
     * @param bool $emerite
     * @return AgentStatut
     */
    public function setEmerite($emerite)
    {
        $this->emerite = $emerite;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRetraite()
    {
        return $this->retraite;
    }

    /**
     * @param bool $retraite
     * @return AgentStatut
     */
    public function setRetraite($retraite)
    {
        $this->retraite = $retraite;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActif()
    {
        if ($this->fin === null) return true;

        try {
            $today = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException('Problème de récupération de la date');

        }
        return $today < $this->fin;
    }

}