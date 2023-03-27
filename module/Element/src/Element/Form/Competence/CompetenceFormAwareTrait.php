<?php

namespace Element\Form\Competence;

trait CompetenceFormAwareTrait {

    private CompetenceForm $competenceForm;

    public function getCompetenceForm() : CompetenceForm
    {
        return $this->competenceForm;
    }

    public function setCompetenceForm(CompetenceForm $competenceForm) : void
    {
        $this->competenceForm = $competenceForm;
    }



}