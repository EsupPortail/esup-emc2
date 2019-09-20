<?php

namespace Application\Form\Competence;

trait CompetenceFormAwareTrait {

    /** @var CompetenceForm $competenceForm */
    private $competenceForm;

    /**
     * @return CompetenceForm
     */
    public function getCompetenceForm()
    {
        return $this->competenceForm;
    }

    /**
     * @param CompetenceForm $competenceForm
     * @return CompetenceForm
     */
    public function setCompetenceForm($competenceForm)
    {
        $this->competenceForm = $competenceForm;
        return $this->competenceForm;
    }



}