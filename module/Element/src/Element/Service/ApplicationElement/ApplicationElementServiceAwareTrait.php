<?php

namespace Element\Service\ApplicationElement;

trait ApplicationElementServiceAwareTrait {

    private ApplicationElementService $applicationElementService;

    public function getApplicationElementService(): ApplicationElementService
    {
        return $this->applicationElementService;
    }

    public function setApplicationElementService(ApplicationElementService $applicationElementService): void
    {
        $this->applicationElementService = $applicationElementService;
    }


}