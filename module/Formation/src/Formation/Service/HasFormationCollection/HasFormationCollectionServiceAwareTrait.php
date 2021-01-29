<?php

namespace Formation\Service\HasFormationCollection;

trait HasFormationCollectionServiceAwareTrait {

    /** @var HasFormationCollectionService */
    private $hasFormationCollectionService;

    /**
     * @return HasFormationCollectionService
     */
    public function getHasFormationCollectionService(): HasFormationCollectionService
    {
        return $this->hasFormationCollectionService;
    }

    /**
     * @param HasFormationCollectionService $hasFormationCollectionService
     * @return HasFormationCollectionService
     */
    public function setHasFormationCollectionService(HasFormationCollectionService $hasFormationCollectionService): HasFormationCollectionService
    {
        $this->hasFormationCollectionService = $hasFormationCollectionService;
        return $this->hasFormationCollectionService;
    }

}