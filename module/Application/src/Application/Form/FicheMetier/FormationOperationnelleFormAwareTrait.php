<?php

namespace Application\Form\FicheMetier;

trait FormationOperationnelleFormAwareTrait {

    /** @var FormationOperationnelleForm $formationOperationnelleForm */
    private $formationOperationnelleForm;

    /**
     * @return FormationOperationnelleForm
     */
    public function getFormationOperationnelleForm()
    {
        return $this->formationOperationnelleForm;
    }

    /**
     * @param FormationOperationnelleForm $formationOperationnelleForm
     * @return FormationOperationnelleForm
     */
    public function setFormationOperationnelleForm($formationOperationnelleForm)
    {
        $this->formationOperationnelleForm = $formationOperationnelleForm;
        return $this->formationOperationnelleForm;
    }


}