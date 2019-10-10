<?php

namespace Application\Form\CompetenceType;

trait CompetenceTypeFormAwareTrait {

    /** @var CompetenceTypeForm */
    private $competenceTypeForm;

    /**
     * @return CompetenceTypeForm
     */
    public function getCompetenceTypeForm()
    {
        return $this->competenceTypeForm;
    }

    /**
     * @param CompetenceTypeForm $competenceTypeForm
     * @return CompetenceTypeForm
     */
    public function setCompetenceTypeForm($competenceTypeForm)
    {
        $this->competenceTypeForm = $competenceTypeForm;
        return $this->competenceTypeForm;
    }

}