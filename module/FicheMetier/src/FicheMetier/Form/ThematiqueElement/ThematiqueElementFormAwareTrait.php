<?php

namespace FicheMetier\Form\ThematiqueElement;

trait ThematiqueElementFormAwareTrait
{
    private ThematiqueElementForm $thematiqueElementForm;

    public function getThematiqueElementForm(): ThematiqueElementForm
    {
        return $this->thematiqueElementForm;
    }

    public function setThematiqueElementForm(ThematiqueElementForm $thematiqueElementForm): void
    {
        $this->thematiqueElementForm = $thematiqueElementForm;
    }

}