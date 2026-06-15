<?php

namespace FicheMetier\Service\FicheMetierConfigurationCompetenceParDefaut;

trait FicheMetierConfigurationCompetenceParDefautServiceAwareTrait
{
    private FicheMetierConfigurationCompetenceParDefautService $ficheMetierConfigurationCompetenceParDefautService;

    public function getFicheMetierConfigurationCompetenceParDefautService(): FicheMetierConfigurationCompetenceParDefautService
    {
        return $this->ficheMetierConfigurationCompetenceParDefautService;
    }

    public function setFicheMetierConfigurationCompetenceParDefautService(FicheMetierConfigurationCompetenceParDefautService $ficheMetierConfigurationCompetenceParDefautService): void
    {
        $this->ficheMetierConfigurationCompetenceParDefautService = $ficheMetierConfigurationCompetenceParDefautService;
    }
}
