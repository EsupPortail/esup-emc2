<?php

namespace Application\Service\CompetenceTheme;

trait CompetenceThemeServiceAwareTrait {

    /** @var CompetenceThemeService $competenceThemeService */
    private $competenceThemeService;

    /**
     * @return CompetenceThemeService
     */
    public function getCompetenceThemeService()
    {
        return $this->competenceThemeService;
    }

    /**
     * @param CompetenceThemeService $competenceThemeService
     * @return CompetenceThemeService
     */
    public function setCompetenceThemeService($competenceThemeService)
    {
        $this->competenceThemeService = $competenceThemeService;
        return $this->competenceThemeService;
    }
}