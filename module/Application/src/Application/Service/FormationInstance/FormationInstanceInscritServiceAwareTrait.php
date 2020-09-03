<?php

namespace Application\Service\FormationInstance;

trait FormationInstanceInscritServiceAwareTrait {

    /** @var FormationInstanceInscritService */
    private $formationInstanceInscritService;

    /**
     * @return FormationInstanceInscritService
     */
    public function getFormationInstanceInscritService()
    {
        return $this->formationInstanceInscritService;
    }

    /**
     * @param FormationInstanceInscritService $formationInstanceInscritService
     * @return FormationInstanceInscritService
     */
    public function setFormationInstanceInscritService(FormationInstanceInscritService $formationInstanceInscritService)
    {
        $this->formationInstanceInscritService = $formationInstanceInscritService;
        return $this->formationInstanceInscritService;
    }


}