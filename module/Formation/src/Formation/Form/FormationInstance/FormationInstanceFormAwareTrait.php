<?php

namespace Formation\Form\FormationInstance;

trait FormationInstanceFormAwareTrait
{

    private FormationInstanceForm $formationInstanceForm;

    public function getFormationInstanceForm(): FormationInstanceForm
    {
        return $this->formationInstanceForm;
    }

    public function setFormationInstanceForm(FormationInstanceForm $formationInstanceForm): void
    {
        $this->formationInstanceForm = $formationInstanceForm;
    }

}