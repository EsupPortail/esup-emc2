<?php

namespace Element\Service\ApplicationTheme;

trait ApplicationThemeServiceAwareTrait {

    /** @var ApplicationThemeService */
    private $applicationGroupeService;

    /**
     * @return ApplicationThemeService
     */
    public function getApplicationThemeService() : ApplicationThemeService
    {
        return $this->applicationGroupeService;
    }

    /**
     * @param ApplicationThemeService $applicationGroupeService
     * @return ApplicationThemeService
     */
    public function setApplicationThemeService(ApplicationThemeService $applicationGroupeService) : ApplicationThemeService
    {
        $this->applicationGroupeService = $applicationGroupeService;
        return $this->applicationGroupeService;
    }
}