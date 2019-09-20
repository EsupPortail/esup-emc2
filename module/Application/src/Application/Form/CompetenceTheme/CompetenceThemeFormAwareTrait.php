<?php

namespace Application\Form\CompetenceTheme;

trait CompetenceThemeFormAwareTrait {

    /** @var CompetenceThemeForm */
    private $competenceThemeForm;

    /**
     * @return CompetenceThemeForm
     */
    public function getCompetenceThemeForm()
    {
        return $this->competenceThemeForm;
    }

    /**
     * @param CompetenceThemeForm $competenceThemeForm
     * @return CompetenceThemeForm
     */
    public function setCompetenceThemeForm($competenceThemeForm)
    {
        $this->competenceThemeForm = $competenceThemeForm;
        return $this->competenceThemeForm;
    }

}