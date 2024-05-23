<?php

namespace Structure\Service\Observateur;

trait ObservateurStructureServiceAwareTrait
{
    protected ObservateurService $observateurStructureService;

    public function getObservateurStructureService(): ObservateurService
    {
        return $this->observateurStructureService;
    }

    public function setObservateurStructureService(ObservateurService $observateurStructureService): void
    {
        $this->observateurStructureService = $observateurStructureService;
    }


}