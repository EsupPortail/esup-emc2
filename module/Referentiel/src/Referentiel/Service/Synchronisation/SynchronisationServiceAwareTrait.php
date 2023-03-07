<?php

namespace Referentiel\Service\Synchronisation;

trait SynchronisationServiceAwareTrait
{

    private SynchronisationService $synchronisationService;

    public function getSynchronisationService(): SynchronisationService
    {
        return $this->synchronisationService;
    }

    public function setSynchronisationService(SynchronisationService $synchronisationService): void
    {
        $this->synchronisationService = $synchronisationService;
    }

}