<?php

namespace Application\Service\Application;

use Application\Service\Application\ApplicationGroupeService;

trait ApplicationGroupeServiceAwareTrait {

    /** @var ApplicationGroupeService */
    private $applicationGroupeService;

    /**
     * @return ApplicationGroupeService
     */
    public function getApplicationGroupeService()
    {
        return $this->applicationGroupeService;
    }

    /**
     * @param ApplicationGroupeService $applicationGroupeService
     * @return ApplicationGroupeService
     */
    public function setApplicationGroupeService($applicationGroupeService)
    {
        $this->applicationGroupeService = $applicationGroupeService;
        return $this->applicationGroupeService;
    }
}