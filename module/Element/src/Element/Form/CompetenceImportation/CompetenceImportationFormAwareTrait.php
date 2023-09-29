<?php

namespace Element\Form\CompetenceImportation;

trait CompetenceImportationFormAwareTrait {

    private CompetenceImportationForm $competenceImportationForm;

    public function getCompetenceImportationForm(): CompetenceImportationForm
    {
        return $this->competenceImportationForm;
    }

    public function setCompetenceImportationForm(CompetenceImportationForm $competenceImportationForm): void
    {
        $this->competenceImportationForm = $competenceImportationForm;
    }

}