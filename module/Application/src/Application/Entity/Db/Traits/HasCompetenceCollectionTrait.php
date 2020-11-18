<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Competence;
use Application\Entity\Db\CompetenceElement;
use Doctrine\Common\Collections\ArrayCollection;

trait HasCompetenceCollectionTrait {

    /** @var ArrayCollection */
    private $competences;

    public function getCompetenceCollection()
    {
        return $this->competences;
    }

    public function getCompetenceListe(bool $avecHisto = false) : array
    {
        $competences = [];
        /** @var CompetenceElement $competenceElement */
        foreach ($this->competences as $competenceElement) {
            if ($avecHisto OR $competenceElement->estNonHistorise()) $competences[] = $competenceElement;
        }
        return $competences;
    }

    public function hasCompetence(Competence $competence) : bool
    {
        /** @var CompetenceElement $competenceElement */
        foreach ($this->competences as $competenceElement) {
            if ($competenceElement->estNonHistorise() AND $competenceElement->getCompetence() === $competence) return true;
        }
        return false;
    }
}