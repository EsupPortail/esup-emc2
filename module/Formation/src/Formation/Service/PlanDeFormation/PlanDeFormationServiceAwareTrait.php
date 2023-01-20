<?php

namespace Formation\Service\PlanDeFormation;

trait PlanDeFormationServiceAwareTrait {

    private PlanDeFormationService $planDeFormationService;

    public function getPlanDeFormationService(): PlanDeFormationService
    {
        return $this->planDeFormationService;
    }

    public function setPlanDeFormationService(PlanDeFormationService $planDeFormationService): void
    {
        $this->planDeFormationService = $planDeFormationService;
    }
}