<?php

namespace Application\Form\FicheMetierType;

trait FormationComportementaleFormAwareTrait {

    /** @var FormationComportementaleForm $formationComportementaleForm */
    private $formationComportementaleForm;

    /**
     * @return FormationComportementaleForm
     */
    public function getFormationComportementaleForm()
    {
        return $this->formationComportementaleForm;
    }

    /**
     * @param FormationComportementaleForm $formationComportementaleForm
     * @return FormationComportementaleForm
     */
    public function setFormationComportementaleForm($formationComportementaleForm)
    {
        $this->formationComportementaleForm = $formationComportementaleForm;
        return $this->formationComportementaleForm;
    }


}