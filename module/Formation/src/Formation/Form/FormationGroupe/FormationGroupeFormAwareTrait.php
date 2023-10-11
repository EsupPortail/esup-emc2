<?php

namespace Formation\Form\FormationGroupe;

trait FormationGroupeFormAwareTrait
{

    private FormationGroupeForm $formationGroupeForm;

    public function getFormationGroupeForm(): FormationGroupeForm
    {
        return $this->formationGroupeForm;
    }

    public function setFormationGroupeForm(FormationGroupeForm $formationGroupeForm): void
    {
        $this->formationGroupeForm = $formationGroupeForm;
    }


}