<?php

namespace Application\Entity\Db\Traits;

use DateTime;

trait HasPeriodeTrait {

    /** @var DateTime|null */
    private $dateDebut;
    /** @var DateTime|null */
    private $dateFin;

    /**
     * @return DateTime|null
     */
    public function getDateDebut() : ?DateTime
    {
        return $this->dateDebut;
    }

    /**
     * @param DateTime|null $date
     * @return self
     */
    public function setDateDebut(?DateTime $date) : self
    {
        $this->dateDebut = $date;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDateFin() : ?DateTime
    {
        return $this->dateFin;
    }

    /**
     * @param DateTime|null $date
     * @return self
     */
    public function setDateFin(?DateTime $date) : self
    {
        $this->dateFin = $date;
        return $this;
    }

    /**
     * @param DateTime|null $date
     * @return bool
     */
    public function estCommence(?DateTime $date = null) : bool
    {
        if ($date === null) $date = new DateTime();
        return ($this->dateDebut <= $date);
    }

    /**
     * @param DateTime|null $date
     * @return bool
     */
    public function estFini(?DateTime $date = null) : bool
    {
        if ($date === null) $date = new DateTime();
        return ($this->dateFin !== null AND $this->dateFin < $date) ;
    }

    /**
     * @param DateTime|null $date
     * @return bool
     */
    public function estEnCours(?DateTime $date = null) : bool
    {
        return ($this->estCommence($date) AND !$this->estFini($date));
    }
}