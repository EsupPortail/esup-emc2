<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Competence;

interface HasCompetenceCollectionInterface {

    public function getCompetenceCollection() ;
    public function getCompetenceListe(bool $avecHisto = false) : array;
    public function hasCompetence(Competence $application) : bool;

}