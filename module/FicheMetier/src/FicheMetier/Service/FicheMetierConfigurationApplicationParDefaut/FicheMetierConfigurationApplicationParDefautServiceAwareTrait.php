<?php

namespace FicheMetier\Service\FicheMetierConfigurationApplicationParDefaut;

trait FicheMetierConfigurationApplicationParDefautServiceAwareTrait
{
    private FicheMetierConfigurationApplicationParDefautService $ficheMetierConfigurationApplicationParDefautService;

    public function getFicheMetierConfigurationApplicationParDefautService(): FicheMetierConfigurationApplicationParDefautService
    {
        return $this->ficheMetierConfigurationApplicationParDefautService;
    }

    public function setFicheMetierConfigurationApplicationParDefautService(FicheMetierConfigurationApplicationParDefautService $ficheMetierConfigurationApplicationParDefautService): void
    {
        $this->ficheMetierConfigurationApplicationParDefautService = $ficheMetierConfigurationApplicationParDefautService;
    }
}
