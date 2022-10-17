<?php

namespace Formation\Service\FormationInstance;

trait FormationInstanceServiceAwareTrait
{

    /** @var FormationInstanceService */
    private FormationInstanceService $formationInstanceService;

    /**
     * @return FormationInstanceService
     */
    public function getFormationInstanceService() : FormationInstanceService
    {
        return $this->formationInstanceService;
    }

    /**
     * @param FormationInstanceService $service
     * @return FormationInstanceService
     */
    public function setFormationInstanceService(FormationInstanceService $service) : FormationInstanceService
    {
        $this->formationInstanceService = $service;
        return $this->formationInstanceService;
    }
}