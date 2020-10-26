<?php

namespace Formation\Form\FormationInstanceFormateur;

trait FormationInstanceFormateurFormAwareTrait {

    /** @var FormationInstanceFormateurForm */
    private $formationInstanceFormateurForm;

    /**
     * @return FormationInstanceFormateurForm
     */
    public function getFormationInstanceFormateurForm(): FormationInstanceFormateurForm
    {
        return $this->formationInstanceFormateurForm;
    }

    /**
     * @param FormationInstanceFormateurForm $formationInstanceFormateurForm
     * @return FormationInstanceFormateurForm
     */
    public function setFormationInstanceFormateurForm(FormationInstanceFormateurForm $formationInstanceFormateurForm): FormationInstanceFormateurForm
    {
        $this->formationInstanceFormateurForm = $formationInstanceFormateurForm;
        return $this->formationInstanceFormateurForm;
    }
}