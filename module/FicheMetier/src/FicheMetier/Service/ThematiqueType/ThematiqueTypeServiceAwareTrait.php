<?php

namespace FicheMetier\Service\ThematiqueType;

trait ThematiqueTypeServiceAwareTrait
{
    private ThematiqueTypeService $thematiqueTypeService;

    public function getThematiqueTypeService(): ThematiqueTypeService
    {
        return $this->thematiqueTypeService;
    }

    public function setThematiqueTypeService(ThematiqueTypeService $thematiqueTypeService): void
    {
        $this->thematiqueTypeService = $thematiqueTypeService;
    }

}