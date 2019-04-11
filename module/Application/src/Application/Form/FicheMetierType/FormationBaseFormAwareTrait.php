<?php

namespace  Application\Form\FicheMetierType;

trait FormationBaseFormAwareTrait {

    /** @var FormationBaseForm $formationBaseForm */
    private $formationBaseForm;

    /**
     * @return FormationBaseForm
     */
    public function getFormationBaseForm()
    {
        return $this->formationBaseForm;
    }

    /**
     * @param FormationBaseForm $formationBaseForm
     * @return FormationBaseForm
     */
    public function setFormationBaseForm($formationBaseForm)
    {
        $this->formationBaseForm = $formationBaseForm;
        return $this->formationBaseForm;
    }


}