<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Competence;
use Application\Entity\Db\CompetenceElement;

interface HasCompetenceCollectionInterface {

    public function getCompetenceCollection() ;
    public function getCompetenceListe(bool $avecHisto = false) : array;
    public function hasCompetence(Competence $competence) : bool;
    public function addCompetenceElement(CompetenceElement $competenceElement) ;
    public function removeCompetenceElement(CompetenceElement $competenceElement) ;

}