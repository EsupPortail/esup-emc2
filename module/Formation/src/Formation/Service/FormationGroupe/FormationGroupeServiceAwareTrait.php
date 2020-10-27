<?php

namespace Formation\Service\FormationGroupe;

trait FormationGroupeServiceAwareTrait {

    /** @var FormationGroupeService */
    private $formationGroupeService;

    /**
     * @return FormationGroupeService
     */
    public function getFormationGroupeService()
    {
        return $this->formationGroupeService;
    }

    /**
     * @param FormationGroupeService $formationGroupeService
     * @return FormationGroupeServiceAwareTrait
     */
    public function setFormationGroupeService($formationGroupeService)
    {
        $this->formationGroupeService = $formationGroupeService;
        return $this;
    }

}