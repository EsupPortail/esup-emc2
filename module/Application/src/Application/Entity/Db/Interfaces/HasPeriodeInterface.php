<?php

namespace Application\Entity\Db\Interfaces;

use DateTime;

interface HasPeriodeInterface {

    /** Accesseur */
    public function getDateDebut();
    public function setDateDebut(?DateTime $date);
    public function getDateFin();
    public function setDateFin(?DateTime $date);

    /** Predicat */
    public function estCommence(?DateTime $date = null) : bool;
    public function estFini(?DateTime $date = null) : bool;
    public function estEnCours(?DateTime $date = null) : bool;
}