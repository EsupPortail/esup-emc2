<?php

namespace Formation\Service\Formation;

trait FormationServiceAwareTrait
{

    private FormationService $formationService;

    public function getFormationService() : FormationService
    {
        return $this->formationService;
    }

    public function setFormationService(FormationService $formationService) : void
    {
        $this->formationService = $formationService;
    }


}