<?php

namespace Formation\Form\FormationElement;

trait FormationElementFormAwareTrait {

    /** @var FormationElementForm */
    private FormationElementForm $formationElementForm;

    public function getFormationElementForm() :FormationElementForm
    {
        return $this->formationElementForm;
    }

    public function setFormationElementForm(FormationElementForm $formationElementForm) : void
    {
        $this->formationElementForm = $formationElementForm;
    }

}