<?php

namespace Formation\Service\FormationTheme;

trait FormationThemeServiceAwareTrait {

    /** @var FormationThemeService */
    private $formationThemeService;

    /**
     * @return FormationThemeService
     */
    public function getFormationThemeService()
    {
        return $this->formationThemeService;
    }

    /**
     * @param FormationThemeService $formationThemeService
     * @return FormationThemeService
     */
    public function setFormationThemeService(FormationThemeService $formationThemeService)
    {
        $this->formationThemeService = $formationThemeService;
        return $this->formationThemeService;
    }
}
