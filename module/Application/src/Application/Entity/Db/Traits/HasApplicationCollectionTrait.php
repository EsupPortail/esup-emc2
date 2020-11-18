<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Application;
use Application\Entity\Db\ApplicationElement;
use Doctrine\Common\Collections\ArrayCollection;

trait HasApplicationCollectionTrait {

    /** @var ArrayCollection */
    private $applications;

    public function getApplicationCollection()
    {
        return $this->applications;
    }

    public function getApplicationListe(bool $avecHisto = false) : array
    {
        $applications = [];
        /** @var ApplicationElement $applicationElement */
        foreach ($this->applications as $applicationElement) {
            if ($avecHisto OR $applicationElement->estNonHistorise()) $applications[] = $applicationElement;
        }
        return $applications;
    }

    public function hasApplication(Application $application) : bool
    {
        /** @var ApplicationElement $applicationElement */
        foreach ($this->applications as $applicationElement) {
            if ($applicationElement->estNonHistorise() AND $applicationElement->getApplication() === $application) return true;
        }
        return false;
    }
}