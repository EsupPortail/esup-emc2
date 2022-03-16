<?php

namespace Element\Form\ApplicationTheme;

trait ApplicationThemeFormAwareTrait {

    /** @var ApplicationThemeForm */
    private $applicationGroupeForm;

    /**
     * @return ApplicationThemeForm
     */
    public function getApplicationThemeForm()
    {
        return $this->applicationGroupeForm;
    }

    /**
     * @param ApplicationThemeForm $applicationGroupeForm
     * @return ApplicationThemeForm
     */
    public function setApplicationThemeForm($applicationGroupeForm)
    {
        $this->applicationGroupeForm = $applicationGroupeForm;
        return $this->applicationGroupeForm;
    }


}