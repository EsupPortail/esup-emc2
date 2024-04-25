<?php

namespace EntretienProfessionnel\Service\Observateur;

trait ObservateurServiceAwareTrait {

    private ObservateurService $observateurService;

    public function getObservateurService(): ObservateurService
    {
        return $this->observateurService;
    }

    public function setObservateurService(ObservateurService $observateurService): void
    {
        $this->observateurService = $observateurService;
    }
}