<?php

namespace Application\Form\FicheMetier;

trait FormationsFormAwareTrait {

    /** @var FormationsForm $formationsForm */
    private $formationsForm;

    /**
     * @return FormationsForm
     */
    public function getFormationsForm()
    {
        return $this->formationsForm;
    }

    /**
     * @param FormationsForm $formationsForm
     * @return FormationsForm
     */
    public function setFormationsForm($formationsForm)
    {
        $this->formationsForm = $formationsForm;
        return $this->formationsForm;
    }


}