<?php

namespace Element\Service\ApplicationElement;

trait ApplicationElementServiceAwareTrait {

    /** @var ApplicationElementService */
    private $applicationElementService;

    /**
     * @return ApplicationElementService
     */
    public function getApplicationElementService(): ApplicationElementService
    {
        return $this->applicationElementService;
    }

    /**
     * @param ApplicationElementService $applicationElementService
     * @return ApplicationElementService
     */
    public function setApplicationElementService(ApplicationElementService $applicationElementService): ApplicationElementService
    {
        $this->applicationElementService = $applicationElementService;
        return $this->applicationElementService;
    }


}