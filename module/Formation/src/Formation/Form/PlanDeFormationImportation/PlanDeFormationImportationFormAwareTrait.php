<?php

namespace Formation\Form\PlanDeFormationImportation;

trait PlanDeFormationImportationFormAwareTrait {

    private PlanDeFormationImportationForm $planDeFormationImportationForm;

    public function getPlanDeFormationImportationForm(): PlanDeFormationImportationForm
    {
        return $this->planDeFormationImportationForm;
    }

    public function setPlanDeFormationImportationForm(PlanDeFormationImportationForm $planDeFormationImportationForm): void
    {
        $this->planDeFormationImportationForm = $planDeFormationImportationForm;
    }

}