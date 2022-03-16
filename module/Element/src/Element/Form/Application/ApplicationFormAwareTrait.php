<?php

namespace Element\Form\Application;

trait ApplicationFormAwareTrait
{
    /** @var ApplicationForm $applicationForm */
    private $applicationForm;

    /**
     * @return ApplicationForm
     */
    public function getApplicationForm() : ApplicationForm
    {
        return $this->applicationForm;
    }

    /**
     * @param ApplicationForm $applicationForm
     * @return ApplicationForm
     */
    public function setApplicationForm(ApplicationForm $applicationForm) : ApplicationForm
    {
        $this->applicationForm = $applicationForm;
        return $this->applicationForm;
    }


}