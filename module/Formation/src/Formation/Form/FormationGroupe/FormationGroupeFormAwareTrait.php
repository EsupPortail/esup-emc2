<?php

namespace Formation\Form\FormationGroupe;

trait FormationGroupeFormAwareTrait
{

    /** @var FormationGroupeForm */
    private $formationGroupeForm;

    /**
     * @return FormationGroupeForm
     */
    public function getFormationGroupeForm()
    {
        return $this->formationGroupeForm;
    }

    /**
     * @param FormationGroupeForm $formationGroupeForm
     * @return FormationGroupeForm
     */
    public function setFormationGroupeForm(FormationGroupeForm $formationGroupeForm)
    {
        $this->formationGroupeForm = $formationGroupeForm;
        return $this->formationGroupeForm;
    }


}