<?php

namespace FicheMetier\Form\ThematiqueType;

trait ThematiqueTypeFormAwareTrait
{
    private ThematiqueTypeForm $thematiqueTypeForm;

    public function getThematiqueTypeForm(): ThematiqueTypeForm
    {
        return $this->thematiqueTypeForm;
    }

    public function setThematiqueTypeForm(ThematiqueTypeForm $thematiqueTypeForm): void
    {
        $this->thematiqueTypeForm = $thematiqueTypeForm;
    }

}