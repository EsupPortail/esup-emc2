<?php

namespace Element\Form\Competence;

trait CompetenceFormAwareTrait {

    /** @var CompetenceForm $competenceForm */
    private $competenceForm;

    /**
     * @return CompetenceForm
     */
    public function getCompetenceForm() : CompetenceForm
    {
        return $this->competenceForm;
    }

    /**
     * @param CompetenceForm $competenceForm
     * @return CompetenceForm
     */
    public function setCompetenceForm(CompetenceForm $competenceForm) : CompetenceForm
    {
        $this->competenceForm = $competenceForm;
        return $this->competenceForm;
    }



}