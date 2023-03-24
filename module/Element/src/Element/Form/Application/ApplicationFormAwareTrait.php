<?php

namespace Element\Form\Application;

trait ApplicationFormAwareTrait
{
    private ApplicationForm $applicationForm;

    public function getApplicationForm() : ApplicationForm
    {
        return $this->applicationForm;
    }

    public function setApplicationForm(ApplicationForm $applicationForm) : void
    {
        $this->applicationForm = $applicationForm;
    }


}