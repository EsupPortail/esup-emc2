<?php

namespace Application\Service\Application;

Trait ApplicationServiceAwareTrait {

    /** @var ApplicationService */
    private $applicationService;

    /**
     * @return ApplicationService
     */
    public function getApplicationService() : ApplicationService
    {
        return $this->applicationService;
    }

    /**
     * @param ApplicationService $applicationService
     * @return ApplicationService
     */
    public function setApplicationService(ApplicationService $applicationService) : ApplicationService
    {
        $this->applicationService = $applicationService;
        return $this->applicationService;
    }

}