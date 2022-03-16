<?php

namespace Element\Form\CompetenceType;

trait CompetenceTypeFormAwareTrait {

    /** @var CompetenceThemeForm */
    private $competenceTypeForm;

    /**
     * @return CompetenceThemeForm
     */
    public function getCompetenceTypeForm()
    {
        return $this->competenceTypeForm;
    }

    /**
     * @param CompetenceThemeForm $competenceTypeForm
     * @return CompetenceThemeForm
     */
    public function setCompetenceTypeForm($competenceTypeForm)
    {
        $this->competenceTypeForm = $competenceTypeForm;
        return $this->competenceTypeForm;
    }

}