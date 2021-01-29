<?php

namespace Formation\Form\FormationElement;

trait FormationElementFormAwareTrait {

    /** @var FormationElementForm */
    private $formationElementForm;

    /**
     * @return FormationElementForm
     */
    public function getFormationElementForm() :FormationElementForm
    {
        return $this->formationElementForm;
    }

    /**
     * @param FormationElementForm $formationElementForm
     * @return FormationElementForm
     */
    public function setFormationElementForm(FormationElementForm $formationElementForm) : FormationElementForm
    {
        $this->formationElementForm = $formationElementForm;
        return $this->formationElementForm;
    }

}