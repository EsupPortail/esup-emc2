<?php

namespace Application\Service\ActiviteDescription;

trait ActiviteDescriptionServiceAwareTrait {

    /** @var ActiviteDescriptionService $activiteDescriptionService */
    private $activiteDescriptionService;

    /**
     * @return ActiviteDescriptionService
     */
    public function getActiviteDescriptionService()
    {
        return $this->activiteDescriptionService;
    }

    /**
     * @param ActiviteDescriptionService $activiteDescriptionService
     * @return ActiviteDescriptionService
     */
    public function setActiviteDescriptionService($activiteDescriptionService)
    {
        $this->activiteDescriptionService = $activiteDescriptionService;
        return $this->activiteDescriptionService;
    }

}