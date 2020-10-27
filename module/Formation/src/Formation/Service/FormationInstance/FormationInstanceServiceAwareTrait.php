<?php

namespace Formation\Service\FormationInstance;

trait FormationInstanceServiceAwareTrait {

    /** @var FormationInstanceService */
    private $formationInstanceService;

    /**
     * @return FormationInstanceService
     */
    public function getFormationInstanceService()
    {
        return $this->formationInstanceService;
    }

    /**
     * @param FormationInstanceService $service
     * @return FormationInstanceService
     */
    public function setFormationInstanceService(FormationInstanceService  $service)
    {
        $this->formationInstanceService = $service;
        return $this->formationInstanceService;
    }
}