<?php

namespace Element\Form\CompetenceTheme;

trait CompetenceThemeFormAwareTrait {

    /** @var CompetenceThemeForm */
    private $competenceThemeForm;

    /**
     * @return CompetenceThemeForm
     */
    public function getCompetenceThemeForm() : CompetenceThemeForm
    {
        return $this->competenceThemeForm;
    }

    /**
     * @param CompetenceThemeForm $competenceThemeForm
     * @return CompetenceThemeForm
     */
    public function setCompetenceThemeForm(CompetenceThemeForm $competenceThemeForm) : CompetenceThemeForm
    {
        $this->competenceThemeForm = $competenceThemeForm;
        return $this->competenceThemeForm;
    }

}