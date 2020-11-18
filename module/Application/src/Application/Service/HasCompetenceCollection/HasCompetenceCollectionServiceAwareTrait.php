<?php

namespace Application\Service\HasCompetenceCollection;

trait HasCompetenceCollectionServiceAwareTrait {

    /** @var HasCompetenceCollectionService */
    private $hasCompetenceCollectionService;

    /**
     * @return HasCompetenceCollectionService
     */
    public function getHasCompetenceCollectionService(): HasCompetenceCollectionService
    {
        return $this->hasCompetenceCollectionService;
    }

    /**
     * @param HasCompetenceCollectionService $hasCompetenceCollectionService
     * @return HasCompetenceCollectionService
     */
    public function setHasCompetenceCollectionService(HasCompetenceCollectionService $hasCompetenceCollectionService): HasCompetenceCollectionService
    {
        $this->hasCompetenceCollectionService = $hasCompetenceCollectionService;
        return $this->hasCompetenceCollectionService;
    }

}