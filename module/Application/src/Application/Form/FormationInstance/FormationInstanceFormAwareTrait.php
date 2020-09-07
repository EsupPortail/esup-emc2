<?php

namespace Application\Form\FormationInstance;

trait FormationInstanceFormAwareTrait {

    /** @var FormationInstanceForm */
    private $formationInstanceForm;

    /**
     * @return FormationInstanceForm
     */
    public function getFormationInstanceForm()
    {
        return $this->formationInstanceForm;
    }

    /**
     * @param FormationInstanceForm $formationInstanceForm
     * @return FormationInstanceFormAwareTrait
     */
    public function setFormationInstanceForm(FormationInstanceForm $formationInstanceForm)
    {
        $this->formationInstanceForm = $formationInstanceForm;
        return $this;
    }

}