<?php

namespace Octopus\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareTrait;

class ImmobilierLocal {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $nom;
    /** @var string */
    private $libelle;
    /** @var string */
    private $libelleCourantFaible;
    /** @var string */
    private $clefSequoia;
    /** @var integer */
    private $clefGestimmo;
    /** @var integer */
    private $affectation;
    /** @var Structure */
    private $structure;
    /** @var ImmobilierNiveau */
    private $niveau;
    /** @var integer */
    private $surface;
    /** @var integer */
    private $nbPlaces;
    /** @var boolean */
    private $accesHandi;
    /** @var boolean */
    private $videoProjecteur;
    /** @var boolean */
    private $tableauBlanc;
    /** @var boolean */
    private $posteInformatique;
    /** @var boolean */
    private $sonorisation;
    /** @var boolean */
    private $sonorisationExceptionnelle;
    /** @var boolean */
    private $occultation;
    /** @var boolean */
    private $accesPmr;
    /** @var integer */
    private $calendrier;

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
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @return string
     */
    public function getLibelleCourantFaible()
    {
        return $this->libelleCourantFaible;
    }

    /**
     * @return string
     */
    public function getClefSequoia()
    {
        return $this->clefSequoia;
    }

    /**
     * @return int
     */
    public function getClefGestimmo()
    {
        return $this->clefGestimmo;
    }

    /**
     * @return int
     */
    public function getAffectation()
    {
        return $this->affectation;
    }

    /**
     * @return Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * @return ImmobilierNiveau
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * @return int
     */
    public function getSurface()
    {
        return $this->surface;
    }

    /**
     * @return int
     */
    public function getNbPlaces()
    {
        return $this->nbPlaces;
    }

    /**
     * @return bool
     */
    public function isAccesHandi()
    {
        return $this->accesHandi;
    }

    /**
     * @return bool
     */
    public function isVideoProjecteur()
    {
        return $this->videoProjecteur;
    }

    /**
     * @return bool
     */
    public function isTableauBlanc()
    {
        return $this->tableauBlanc;
    }

    /**
     * @return bool
     */
    public function isPosteInformatique()
    {
        return $this->posteInformatique;
    }

    /**
     * @return bool
     */
    public function isSonorisation()
    {
        return $this->sonorisation;
    }

    /**
     * @return bool
     */
    public function isSonorisationExceptionnelle()
    {
        return $this->sonorisationExceptionnelle;
    }

    /**
     * @return bool
     */
    public function isOccultation()
    {
        return $this->occultation;
    }

    /**
     * @return bool
     */
    public function isAccesPmr()
    {
        return $this->accesPmr;
    }

    /**
     * @return int
     */
    public function getCalendrier()
    {
        return $this->calendrier;
    }




}