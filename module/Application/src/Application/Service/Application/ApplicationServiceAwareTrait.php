<?php

namespace Application\Service\Application;

Trait ApplicationServiceAwareTrait {

    /** @var ApplicationService */
    private $applicationService;

    /**
     * @return ApplicationService
     */
    public function getApplicationService()
    {
        return $this->applicationService;
    }

    /**
     * @param ApplicationService $applicationService
     * @return ApplicationService
     */
    public function setApplicationService($applicationService)
    {
        $this->applicationService = $applicationService;
        return $this->applicationService;
    }

}