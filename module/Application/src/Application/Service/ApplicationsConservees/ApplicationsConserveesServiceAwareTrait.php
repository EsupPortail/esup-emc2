<?php

namespace Application\Service\ApplicationsConservees;

trait ApplicationsConserveesServiceAwareTrait {

    /** @var ApplicationsConserveesService $applicationsConserveesService */
    private $applicationsConserveesService;

    /**
     * @return ApplicationsConserveesService
     */
    public function getApplicationsConserveesService()
    {
        return $this->applicationsConserveesService;
    }

    /**
     * @param ApplicationsConserveesService $applicationsConserveesService
     * @return ApplicationsConserveesService
     */
    public function setApplicationsConserveesService($applicationsConserveesService)
    {
        $this->applicationsConserveesService = $applicationsConserveesService;
        return $this->applicationsConserveesService;
    }

}