<?php

namespace Element\Service\ApplicationGroupe;

trait ApplicationGroupeServiceAwareTrait {

    /** @var ApplicationGroupeService */
    private $applicationGroupeService;

    /**
     * @return ApplicationGroupeService
     */
    public function getApplicationGroupeService() : ApplicationGroupeService
    {
        return $this->applicationGroupeService;
    }

    /**
     * @param ApplicationGroupeService $applicationGroupeService
     * @return ApplicationGroupeService
     */
    public function setApplicationGroupeService(ApplicationGroupeService $applicationGroupeService) : ApplicationGroupeService
    {
        $this->applicationGroupeService = $applicationGroupeService;
        return $this->applicationGroupeService;
    }
}