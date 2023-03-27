<?php

namespace Element\Form\ApplicationTheme;

trait ApplicationThemeFormAwareTrait {

    private ApplicationThemeForm $applicationGroupeForm;

    public function getApplicationThemeForm() : ApplicationThemeForm
    {
        return $this->applicationGroupeForm;
    }

    public function setApplicationThemeForm(ApplicationThemeForm $applicationGroupeForm) : void
    {
        $this->applicationGroupeForm = $applicationGroupeForm;
    }


}