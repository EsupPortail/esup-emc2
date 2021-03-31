<?php

namespace Formation\Entity\Db\Traits;

use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationElement;
use Doctrine\Common\Collections\ArrayCollection;

trait HasFormationCollectionTrait {

    /** @var ArrayCollection */
    private $formations;

    public function getFormationCollection()
    {
        return $this->formations;
    }

    /**
     * @param bool $avecHisto
     * @return FormationElement[]
     */
    public function getFormationListe(bool $avecHisto = false) : array
    {
        $formations = [];
        /** @var FormationElement $formationElement */
        foreach ($this->formations as $formationElement) {
            if ($avecHisto OR $formationElement->estNonHistorise()) $formations[$formationElement->getFormation()->getId()] = $formationElement;
        }
        return $formations;
    }

    public function getFormationDictionnaire()
    {
        $dictionnaire = [];
        foreach ($this->formations as $formationElement) {
            $element = [];
            $element['entite'] = $formationElement;
            $element['raison'] = null;
            $element['conserve'] = true;
            $dictionnaire[] = $element;
        }
        return $dictionnaire;
    }

    public function hasFormation(Formation $formation) : ?FormationElement
    {
        /** @var FormationElement $formationElement */
        foreach ($this->formations as $formationElement) {
            if ($formationElement->estNonHistorise() AND $formationElement->getFormation() === $formation) return $formationElement;
        }
        return null;
    }
}