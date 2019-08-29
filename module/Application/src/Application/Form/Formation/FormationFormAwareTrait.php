<?php

namespace Application\Form\Formation;

trait FormationFormAwareTrait {

    /** @var FormationForm */
    private $formationForm;

    /**
     * @return FormationForm
     */
    public function getFormationForm()
    {
        return $this->formationForm;
    }

    /**
     * @param FormationForm $formationForm
     * @return FormationForm
     */
    public function setFormationForm($formationForm)
    {
        $this->formationForm = $formationForm;
        return $this->formationForm;
    }

}