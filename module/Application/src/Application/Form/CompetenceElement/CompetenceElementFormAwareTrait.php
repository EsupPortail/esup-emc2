<?php

namespace Application\Form\CompetenceElement;

trait CompetenceElementFormAwareTrait {

    /** @var CompetenceElementForm */
    private $competenceElementForm;

    /**
     * @return CompetenceElementForm
     */
    public function getCompetenceElementForm() :CompetenceElementForm
    {
        return $this->competenceElementForm;
    }

    /**
     * @param CompetenceElementForm $competenceElementForm
     * @return CompetenceElementForm
     */
    public function setApplicationElementForm(CompetenceElementForm $competenceElementForm) : CompetenceElementForm
    {
        $this->competenceElementForm = $competenceElementForm;
        return $this->competenceElementForm;
    }

}