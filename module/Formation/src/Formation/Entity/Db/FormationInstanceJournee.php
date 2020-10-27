<?php

namespace Formation\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class FormationInstanceJournee implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var FormationInstance */
    private $instance;
    /** @var string */
    private $jour;
    /** @var string */
    private $debut;
    /** @var string */
    private $fin;
    /** @var string */
    private $lieu;
    /** @var string */
    private $remarque;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FormationInstance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstanceJournee
     */
    public function setInstance(FormationInstance $instance)
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * @return string
     */
    public function getJour()
    {
        return $this->jour;
    }

    /**
     * @param string $jour
     * @return FormationInstanceJournee
     */
    public function setJour(string $jour)
    {
        $this->jour = $jour;
        return $this;
    }

    /**
     * @return string
     */
    public function getDebut()
    {
        return $this->debut;
    }

    /**
     * @param string $debut
     * @return FormationInstanceJournee
     */
    public function setDebut(string $debut)
    {
        $this->debut = $debut;
        return $this;
    }

    /**
     * @return string
     */
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * @param string $fin
     * @return FormationInstanceJournee
     */
    public function setFin(string $fin)
    {
        $this->fin = $fin;
        return $this;
    }

    /**
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * @param string $lieu
     * @return FormationInstanceJournee
     */
    public function setLieu(string $lieu)
    {
        $this->lieu = $lieu;
        return $this;
    }

    /**
     * @return string
     */
    public function getRemarque()
    {
        return $this->remarque;
    }

    /**
     * @param string $remarque
     * @return FormationInstanceJournee
     */
    public function setRemarque(string $remarque)
    {
        $this->remarque = $remarque;
        return $this;
    }

}