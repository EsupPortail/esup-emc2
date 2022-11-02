<?php

namespace Element\Service\Application;

Trait ApplicationServiceAwareTrait {

    private ApplicationService $applicationService;

    public function getApplicationService() : ApplicationService
    {
        return $this->applicationService;
    }

    public function setApplicationService(ApplicationService $applicationService) : void
    {
        $this->applicationService = $applicationService;
    }

}