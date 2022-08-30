<?php

namespace Formation\Service\DemandeExterne;

trait DemandeExterneServiceAwareTrait {

    private DemandeExterneService $demandeExterneService;

    /**
     * @return DemandeExterneService
     */
    public function getDemandeExterneService(): DemandeExterneService
    {
        return $this->demandeExterneService;
    }

    /**
     * @param DemandeExterneService $demandeExterneService
     */
    public function setDemandeExterneService(DemandeExterneService $demandeExterneService): void
    {
        $this->demandeExterneService = $demandeExterneService;
    }

}