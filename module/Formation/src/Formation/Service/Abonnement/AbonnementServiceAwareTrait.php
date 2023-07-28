<?php

namespace Formation\Service\Abonnement;

trait AbonnementServiceAwareTrait {

    private AbonnementService $abonnementService;

    public function getAbonnementService(): AbonnementService
    {
        return $this->abonnementService;
    }

    public function setAbonnementService(AbonnementService $abonnementService): void
    {
        $this->abonnementService = $abonnementService;
    }

}