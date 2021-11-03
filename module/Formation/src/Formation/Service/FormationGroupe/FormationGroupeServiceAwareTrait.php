<?php

namespace Formation\Service\FormationGroupe;

trait FormationGroupeServiceAwareTrait
{

    /** @var FormationGroupeService */
    private $formationGroupeService;

    /**
     * @return FormationGroupeService
     */
    public function getFormationGroupeService() : FormationGroupeService
    {
        return $this->formationGroupeService;
    }

    /**
     * @param FormationGroupeService $formationGroupeService
     * @return FormationGroupeService
     */
    public function setFormationGroupeService(FormationGroupeService $formationGroupeService) : FormationGroupeService
    {
        $this->formationGroupeService = $formationGroupeService;
        return $this->formationGroupeService;
    }

}