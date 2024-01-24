<?php

namespace FicheMetier\Service\ThematiqueElement;

trait ThematiqueElementServiceAwareTrait
{
    private ThematiqueElementService $thematiqueElementService;

    public function getThematiqueElementService(): ThematiqueElementService
    {
        return $this->thematiqueElementService;
    }

    public function setThematiqueElementService(ThematiqueElementService $thematiqueElementService): void
    {
        $this->thematiqueElementService = $thematiqueElementService;
    }

}