<?php

namespace Element\Entity\Db\Traits;

use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use Doctrine\Common\Collections\ArrayCollection;

trait HasCompetenceCollectionTrait {

    /** @var ArrayCollection */
    private $competences;

    public function getCompetenceCollection()
    {
        return $this->competences;
    }

    /** @return CompetenceElement[] */
    public function getCompetenceListe(bool $avecHisto = false) : array
    {
        $competences = [];
        /** @var CompetenceElement $competenceElement */
        foreach ($this->competences as $competenceElement) {
            if ($avecHisto OR $competenceElement->estNonHistorise()) $competences[$competenceElement->getCompetence()->getId()] = $competenceElement;
        }
        return $competences;
    }

    public function getCompetenceDictionnaire()
    {
        $dictionnaire = [];
        foreach ($this->competences as $competenceElement) {
            $element = [];
            $element['entite'] = $competenceElement;
            $element['raison'][] = $this;
            $element['conserve'] = true;
            $dictionnaire[$competenceElement->getCompetence()->getId()] = $element;
        }
        return $dictionnaire;
    }

    public function hasCompetence(Competence $competence) : bool
    {
        /** @var CompetenceElement $competenceElement */
        foreach ($this->competences as $competenceElement) {
            if ($competenceElement->estNonHistorise() AND $competenceElement->getCompetence() === $competence) return true;
        }
        return false;
    }

    public function addCompetenceElement(CompetenceElement $element) : void
    {
        $this->competences->add($element);
    }

    public function removeCompetenceElement(CompetenceElement $element) : void
    {
        $this->competences->removeElement($element);
    }
}