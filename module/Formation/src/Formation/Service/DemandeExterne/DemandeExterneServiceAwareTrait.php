<?php

namespace Formation\Service\DemandeExterne;

trait DemandeExterneServiceAwareTrait {

    private DemandeExterneService $demandeExterneService;

    public function getDemandeExterneService(): DemandeExterneService
    {
        return $this->demandeExterneService;
    }

    public function setDemandeExterneService(DemandeExterneService $demandeExterneService): void
    {
        $this->demandeExterneService = $demandeExterneService;
    }

}