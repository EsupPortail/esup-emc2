<?php

namespace Formation\Form\FormationInstanceFrais;

trait FormationInstanceFraisFormAwareTrait {

    /** @var FormationInstanceFraisForm */
    private $formationInstanceFraisForm;

    /**
     * @return FormationInstanceFraisForm
     */
    public function getFormationInstanceFraisForm(): FormationInstanceFraisForm
    {
        return $this->formationInstanceFraisForm;
    }

    /**
     * @param FormationInstanceFraisForm $formationInstanceFraisForm
     * @return FormationInstanceFraisForm
     */
    public function setFormationInstanceFraisForm(FormationInstanceFraisForm $formationInstanceFraisForm): FormationInstanceFraisForm
    {
        $this->formationInstanceFraisForm = $formationInstanceFraisForm;
        return $this->formationInstanceFraisForm;
    }

}