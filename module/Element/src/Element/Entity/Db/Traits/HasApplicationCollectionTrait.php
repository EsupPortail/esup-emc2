<?php

namespace Element\Entity\Db\Traits;

use Doctrine\Common\Collections\Collection;
use Element\Entity\Db\Application;
use Element\Entity\Db\ApplicationElement;

trait HasApplicationCollectionTrait
{

    private Collection $applications;

    public function getApplicationCollection() : Collection
    {
        return $this->applications;
    }

    public function getApplicationListe(bool $avecHisto = false): array
    {
        $applications = [];
        /** @var ApplicationElement $applicationElement */
        foreach ($this->applications as $applicationElement) {
            if ($avecHisto or $applicationElement->estNonHistorise()) $applications[$applicationElement->getApplication()->getId()] = $applicationElement;
        }
        return $applications;
    }

    public function getApplicationDictionnaire() : array
    {
        $dictionnaire = [];
        foreach ($this->applications as $applicationElement) {
            $element = [];
            $element['entite'] = $applicationElement;
            $element['raison'][] = $this;
            $element['conserve'] = true;
            $dictionnaire[$applicationElement->getApplication()->getId()] = $element;
        }
        return $dictionnaire;
    }

    public function hasApplication(Application $application): ?ApplicationElement
    {
        /** @var ApplicationElement $applicationElement */
        foreach ($this->applications as $applicationElement) {
            if ($applicationElement->estNonHistorise() and $applicationElement->getApplication() === $application) return $applicationElement;
        }
        return null;
    }

    public function addApplicationElement(ApplicationElement $element) : void
    {
        $this->applications->add($element);
    }

    public function removeApplicationElement(ApplicationElement $element) : void
    {
        $this->applications->removeElement($element);
    }

    public function clearApplications() : void
    {
        $this->applications->clear();
    }
}