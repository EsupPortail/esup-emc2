<?php

namespace Element\Form\CompetenceDiscipline;

trait CompetenceDisciplineFormAwareTrait {

    private CompetenceDisciplineForm $competenceDisciplineForm;

    public function getCompetenceDisciplineForm() : CompetenceDisciplineForm
    {
        return $this->competenceDisciplineForm;
    }

    public function setCompetenceDisciplineForm(CompetenceDisciplineForm $competenceDisciplineForm) : void
    {
        $this->competenceDisciplineForm = $competenceDisciplineForm;
    }

}