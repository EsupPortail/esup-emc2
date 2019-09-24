<?php

namespace Application\Form\FicheMetier;

trait GererCompetenceFormAwareTrait {

    /** @var GererCompetenceForm */
    private $gererCompetenceForm;

    /**
     * @return GererCompetenceForm
     */
    public function getGererCompetenceForm()
    {
        return $this->gererCompetenceForm;
    }

    /**
     * @param GererCompetenceForm $gererCompetenceForm
     * @return GererCompetenceForm
     */
    public function setGererCompetenceForm($gererCompetenceForm)
    {
        $this->gererCompetenceForm = $gererCompetenceForm;
        return $this->gererCompetenceForm;
    }


}