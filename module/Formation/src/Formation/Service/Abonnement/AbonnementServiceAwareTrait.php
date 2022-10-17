<?php

namespace Formation\Service\Abonnement;

trait AbonnementServiceAwareTrait {

    /** @var AbonnementService */
    private $abonnementService;

    /**
     * @return AbonnementService
     */
    public function getAbonnementService(): AbonnementService
    {
        return $this->abonnementService;
    }

    /**
     * @param AbonnementService $abonnementService
     */
    public function setAbonnementService(AbonnementService $abonnementService): void
    {
        $this->abonnementService = $abonnementService;
    }

}