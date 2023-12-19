<?php

namespace Formation\Service\HasFormationCollection;

trait HasFormationCollectionServiceAwareTrait
{

    private HasFormationCollectionService $hasFormationCollectionService;

    public function getHasFormationCollectionService(): HasFormationCollectionService
    {
        return $this->hasFormationCollectionService;
    }

    public function setHasFormationCollectionService(HasFormationCollectionService $hasFormationCollectionService): void
    {
        $this->hasFormationCollectionService = $hasFormationCollectionService;
    }

}