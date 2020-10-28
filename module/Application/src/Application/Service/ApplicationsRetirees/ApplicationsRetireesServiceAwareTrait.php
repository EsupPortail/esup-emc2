<?php

namespace Application\Service\ApplicationsRetirees;

trait ApplicationsRetireesServiceAwareTrait {

    /** @var ApplicationsRetireesService $applicationsRetireesService */
    private $applicationsRetireesService;

    /**
     * @return ApplicationsRetireesService
     */
    public function getApplicationsRetireesService() : ApplicationsRetireesService
    {
        return $this->applicationsRetireesService;
    }

    /**
     * @param ApplicationsRetireesService $applicationsRetireesService
     * @return ApplicationsRetireesService
     */
    public function setApplicationsRetireesService(ApplicationsRetireesService $applicationsRetireesService) : ApplicationsRetireesService
    {
        $this->applicationsRetireesService = $applicationsRetireesService;
        return $this->applicationsRetireesService;
    }

}