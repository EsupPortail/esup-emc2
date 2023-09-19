<?php

namespace Formation\Form\SelectionPlanDeFormation;

trait SelectionPlanDeFormationFormAwareTrait
{
    private SelectionPlanDeFormationForm $selectionPlanDeFormationForm;

    public function getSelectionPlanDeFormationForm(): SelectionPlanDeFormationForm
    {
        return $this->selectionPlanDeFormationForm;
    }

    public function setSelectionPlanDeFormationForm(SelectionPlanDeFormationForm $selectionPlanDeFormationForm): void
    {
        $this->selectionPlanDeFormationForm = $selectionPlanDeFormationForm;
    }

}
