<?php

namespace Formation\Entity\Db\Interfaces;

use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationElement;

interface HasFormationCollectionInterface {

    public function getFormationCollection() ;
    public function getFormationListe(bool $avecHisto = false) : array;
    public function hasFormation(Formation $formation) : ?FormationElement;

}