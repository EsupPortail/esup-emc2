<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Application;
use Application\Entity\Db\ApplicationElement;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenValidation\Entity\Db\ValidationInstance;

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
        /** @var ApplicationElement $activiteApplication */
        foreach ($this->applications as $activiteApplication) {
            if ($avecHisto OR $activiteApplication->estNonHistorise()) $applications[] = $activiteApplication;
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

    // Quid de cela
    public function addApplication(Application $application, string $complement = null, ValidationInstance $validation = null) {}
    public function removeApplication(Application $application) {}
    public function clearApplications() {}

}