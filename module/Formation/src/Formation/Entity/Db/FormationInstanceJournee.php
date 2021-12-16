<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Entity\Db\Traits\HasSourceTrait;
use DateTime;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class FormationInstanceJournee implements HistoriqueAwareInterface, HasSourceInterface
{
    use HistoriqueAwareTrait;
    use HasSourceTrait;

    /** @var integer */
    private $id;
    /** @var FormationInstance */
    private $instance;
    /** @var DateTime */
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
     * @return DateTime
     */
    public function getJour()
    {
        return $this->jour;
    }

    /**
     * @param DateTime $jour
     * @return FormationInstanceJournee
     */
    public function setJour(DateTime $jour)
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
     * @param string|null $debut
     * @return FormationInstanceJournee
     */
    public function setDebut(?string $debut)
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
     * @param string|null $fin
     * @return FormationInstanceJournee
     */
    public function setFin(?string $fin)
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
     * @param string|null $lieu
     * @return FormationInstanceJournee
     */
    public function setLieu(?string $lieu)
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
     * @param string|null $remarque
     * @return FormationInstanceJournee
     */
    public function setRemarque(?string $remarque)
    {
        $this->remarque = $remarque;
        return $this;
    }

}