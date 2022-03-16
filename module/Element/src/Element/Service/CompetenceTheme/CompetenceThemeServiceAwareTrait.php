<?php

namespace Element\Service\CompetenceTheme;

trait CompetenceThemeServiceAwareTrait {

    /** @var CompetenceThemeService $competenceThemeService */
    private $competenceThemeService;

    /**
     * @return CompetenceThemeService
     */
    public function getCompetenceThemeService() : CompetenceThemeService
    {
        return $this->competenceThemeService;
    }

    /**
     * @param CompetenceThemeService $competenceThemeService
     * @return CompetenceThemeService
     */
    public function setCompetenceThemeService(CompetenceThemeService $competenceThemeService) : CompetenceThemeService
    {
        $this->competenceThemeService = $competenceThemeService;
        return $this->competenceThemeService;
    }
}