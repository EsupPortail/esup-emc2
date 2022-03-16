<?php

namespace Element\Service\HasApplicationCollection;

trait HasApplicationCollectionServiceAwareTrait {

    /** @var HasApplicationCollectionService */
    private $hasApplicationCollectionService;

    /**
     * @return HasApplicationCollectionService
     */
    public function getHasApplicationCollectionService(): HasApplicationCollectionService
    {
        return $this->hasApplicationCollectionService;
    }

    /**
     * @param HasApplicationCollectionService $hasApplicationCollectionService
     * @return HasApplicationCollectionService
     */
    public function setHasApplicationCollectionService(HasApplicationCollectionService $hasApplicationCollectionService): HasApplicationCollectionService
    {
        $this->hasApplicationCollectionService = $hasApplicationCollectionService;
        return $this->hasApplicationCollectionService;
    }

}