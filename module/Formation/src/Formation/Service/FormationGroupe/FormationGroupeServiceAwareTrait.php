<?php

namespace Formation\Service\FormationGroupe;

trait FormationGroupeServiceAwareTrait
{

    private FormationGroupeService $formationGroupeService;

    public function getFormationGroupeService() : FormationGroupeService
    {
        return $this->formationGroupeService;
    }

    public function setFormationGroupeService(FormationGroupeService $formationGroupeService) : void
    {
        $this->formationGroupeService = $formationGroupeService;
    }

}