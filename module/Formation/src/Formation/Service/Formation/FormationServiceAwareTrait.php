<?php

namespace Formation\Service\Formation;

trait FormationServiceAwareTrait {

    /** @var FormationService $formationService */
    private $formationService;

    /**
     * @return FormationService
     */
    public function getFormationService()
    {
        return $this->formationService;
    }

    /**
     * @param FormationService $formationService
     * @return FormationService
     */
    public function setFormationService($formationService)
    {
        $this->formationService = $formationService;
        return $this->formationService;
    }


}