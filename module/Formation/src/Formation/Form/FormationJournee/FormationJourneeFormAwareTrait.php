<?php

namespace Formation\Form\FormationJournee;

trait FormationJourneeFormAwareTrait
{

    /** @var FormationJourneeForm */
    private $formationJourneeForm;

    /**
     * @return FormationJourneeForm
     */
    public function getFormationJourneeForm()
    {
        return $this->formationJourneeForm;
    }

    /**
     * @param FormationJourneeForm $formationJourneeForm
     * @return FormationJourneeFormAwareTrait
     */
    public function setFormationJourneeForm(FormationJourneeForm $formationJourneeForm)
    {
        $this->formationJourneeForm = $formationJourneeForm;
        return $this;
    }


}