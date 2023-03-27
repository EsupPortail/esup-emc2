<?php

namespace Element\Form\CompetenceType;

trait CompetenceTypeFormAwareTrait {

    private CompetenceTypeForm  $competenceTypeForm;

    public function getCompetenceTypeForm() : CompetenceTypeForm
    {
        return $this->competenceTypeForm;
    }

    public function setCompetenceTypeForm(CompetenceTypeForm $competenceTypeForm) : void
    {
        $this->competenceTypeForm = $competenceTypeForm;
    }

}