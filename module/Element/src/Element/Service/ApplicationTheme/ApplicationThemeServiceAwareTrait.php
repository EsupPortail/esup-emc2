<?php

namespace Element\Service\ApplicationTheme;

trait ApplicationThemeServiceAwareTrait {

    private ApplicationThemeService $applicationGroupeService;

    public function getApplicationThemeService() : ApplicationThemeService
    {
        return $this->applicationGroupeService;
    }

    public function setApplicationThemeService(ApplicationThemeService $applicationGroupeService) : void
    {
        $this->applicationGroupeService = $applicationGroupeService;
    }
}