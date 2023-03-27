<?php

namespace Element\Form\CompetenceTheme;

trait CompetenceThemeFormAwareTrait {

    private CompetenceThemeForm $competenceThemeForm;

    public function getCompetenceThemeForm() : CompetenceThemeForm
    {
        return $this->competenceThemeForm;
    }

    public function setCompetenceThemeForm(CompetenceThemeForm $competenceThemeForm) : void
    {
        $this->competenceThemeForm = $competenceThemeForm;
    }

}