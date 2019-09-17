<?php

namespace Application\Form\FormationTheme;

trait FormationThemeFormAwareTrait {

    /** @var FormationThemeForm */
    private $formationThemeForm;

    /**
     * @return FormationThemeForm
     */
    public function getFormationThemeForm()
    {
        return $this->formationThemeForm;
    }

    /**
     * @param FormationThemeForm $formationThemeForm
     * @return FormationThemeForm
     */
    public function setFormationThemeForm($formationThemeForm)
    {
        $this->formationThemeForm = $formationThemeForm;
        return $this->formationThemeForm;
    }

}