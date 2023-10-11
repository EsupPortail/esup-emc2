<?php

namespace Formation\Form\FormationInstanceFrais;

trait FormationInstanceFraisFormAwareTrait
{

    private FormationInstanceFraisForm $formationInstanceFraisForm;

    public function getFormationInstanceFraisForm(): FormationInstanceFraisForm
    {
        return $this->formationInstanceFraisForm;
    }

    public function setFormationInstanceFraisForm(FormationInstanceFraisForm $formationInstanceFraisForm): void
    {
        $this->formationInstanceFraisForm = $formationInstanceFraisForm;
    }

}