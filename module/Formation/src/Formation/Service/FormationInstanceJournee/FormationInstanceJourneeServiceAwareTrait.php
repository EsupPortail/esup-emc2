<?php

namespace Formation\Service\FormationInstanceJournee;

trait FormationInstanceJourneeServiceAwareTrait
{

    /** @var FormationInstanceJourneeService */
    private $formationInstanceJourneeService;

    /**
     * @return FormationInstanceJourneeService
     */
    public function getFormationInstanceJourneeService()
    {
        return $this->formationInstanceJourneeService;
    }

    /**
     * @param FormationInstanceJourneeService $formationInstanceJourneeService
     * @return FormationInstanceJourneeService
     */
    public function setFormationInstanceJourneeService(FormationInstanceJourneeService $formationInstanceJourneeService)
    {
        $this->formationInstanceJourneeService = $formationInstanceJourneeService;
        return $this->formationInstanceJourneeService;
    }

}