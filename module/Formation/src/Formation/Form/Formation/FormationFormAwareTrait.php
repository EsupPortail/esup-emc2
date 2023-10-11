<?php

namespace Formation\Form\Formation;

trait FormationFormAwareTrait
{

    private FormationForm $formationForm;

    public function getFormationForm(): FormationForm
    {
        return $this->formationForm;
    }

    public function setFormationForm(FormationForm $formationForm): FormationForm
    {
        $this->formationForm = $formationForm;
        return $this->formationForm;
    }

}