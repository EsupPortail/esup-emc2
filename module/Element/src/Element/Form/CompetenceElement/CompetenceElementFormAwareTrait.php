<?php

namespace Element\Form\CompetenceElement;

trait CompetenceElementFormAwareTrait {

    private CompetenceElementForm $competenceElementForm;

    public function getCompetenceElementForm() :CompetenceElementForm
    {
        return $this->competenceElementForm;
    }

    public function setCompetenceElementForm(CompetenceElementForm $competenceElementForm) : void
    {
        $this->competenceElementForm = $competenceElementForm;
    }

}