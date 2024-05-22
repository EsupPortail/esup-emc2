<?php

namespace Structure\Service\Observateur;

trait ObservateurServiceAwareTrait
{
    protected ObservateurService $observateurService;

    public function getObservateurService(): ObservateurService
    {
        return $this->observateurService;
    }

    public function setObservateurService(ObservateurService $observateurService): void
    {
        $this->observateurService = $observateurService;
    }


}