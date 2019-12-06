<?php

namespace Application\Service\ApplicationsRetirees;

trait ApplicationsRetireesServiceAwareTrait {

    /** @var ApplicationsRetireesService $applicationsRetireesService */
    private $applicationsRetireesService;

    /**
     * @return ApplicationsRetireesService
     */
    public function getApplicationsRetireesService()
    {
        return $this->applicationsRetireesService;
    }

    /**
     * @param ApplicationsRetireesService $applicationsRetireesService
     * @return ApplicationsRetireesService
     */
    public function setApplicationsRetireesService($applicationsRetireesService)
    {
        $this->applicationsRetireesService = $applicationsRetireesService;
        return $this->applicationsRetireesService;
    }

}