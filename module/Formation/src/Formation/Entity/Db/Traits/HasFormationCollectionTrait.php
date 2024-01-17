<?php

namespace Formation\Entity\Db\Traits;

use Doctrine\Common\Collections\Collection;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationElement;

trait HasFormationCollectionTrait {

    private Collection $formations;

    public function getFormationCollection(): Collection
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

    public function getFormationDictionnaire(): array
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

    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function hasFormation(Formation $formation) : bool
    {
        /** @var FormationElement $formationElement */
        foreach ($this->formations as $formationElement) {
            if ($formationElement instanceof FormationElement) $formation_ = $formationElement->getFormation(); else $formation_ = $formationElement;
            if ($formationElement->estNonHistorise() AND $formation_ === $formation) return true;
        }
        return false;
    }

    public function addFormation(Formation $formation): void
    {
        $this->formations->add($formation);
    }

    public function removeFormation(Formation $formation): void
    {
        $this->formations->removeElement($formation);
    }
}