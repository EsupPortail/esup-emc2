<?php

namespace Formation\Service\Axe;

trait AxeServiceAwareTrait {

    private AxeService $axeService;

    public function getAxeService(): AxeService
    {
        return $this->axeService;
    }

    public function setAxeService(AxeService $axeService): void
    {
        $this->axeService = $axeService;
    }

}