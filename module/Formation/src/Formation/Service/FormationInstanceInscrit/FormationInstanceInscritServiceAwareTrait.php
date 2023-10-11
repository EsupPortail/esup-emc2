<?php

namespace Formation\Service\FormationInstanceInscrit;

trait FormationInstanceInscritServiceAwareTrait
{
    private FormationInstanceInscritService $formationInstanceInscritService;

    public function getFormationInstanceInscritService(): FormationInstanceInscritService
    {
        return $this->formationInstanceInscritService;
    }

    public function setFormationInstanceInscritService(FormationInstanceInscritService $formationInstanceInscritService): void
    {
        $this->formationInstanceInscritService = $formationInstanceInscritService;
    }
}