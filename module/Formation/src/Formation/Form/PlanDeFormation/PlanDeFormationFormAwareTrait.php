<?php

namespace Formation\Form\PlanDeFormation;

trait PlanDeFormationFormAwareTrait {

    private PlanDeFormationForm $planDeFormationForm;

    public function getPlanDeFormationForm(): PlanDeFormationForm
    {
        return $this->planDeFormationForm;
    }

    public function setPlanDeFormationForm(PlanDeFormationForm $planDeFormationForm): void
    {
        $this->planDeFormationForm = $planDeFormationForm;
    }
}