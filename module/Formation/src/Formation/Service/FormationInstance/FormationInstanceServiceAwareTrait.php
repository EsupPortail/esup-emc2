<?php

namespace Formation\Service\FormationInstance;

trait FormationInstanceServiceAwareTrait
{

    private FormationInstanceService $formationInstanceService;

    public function getFormationInstanceService() : FormationInstanceService
    {
        return $this->formationInstanceService;
    }

    public function setFormationInstanceService(FormationInstanceService $service) : void
    {
        $this->formationInstanceService = $service;
    }
}