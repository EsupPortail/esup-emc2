<?php

namespace Element\Entity\Db\Interfaces;

use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;

interface HasCompetenceCollectionInterface {

    public function getCompetenceCollection() ;
    public function getCompetenceListe(bool $avecHisto = false) : array;
    public function hasCompetence(Competence $competence) : bool;
    public function addCompetenceElement(CompetenceElement $competenceElement) ;
    public function removeCompetenceElement(CompetenceElement $competenceElement) ;

}