<?php

namespace Application\Form\Application;

trait ApplicationFormAwareTrait
{
    /** @var ApplicationForm $applicationForm */
    private $applicationForm;

    /**
     * @return ApplicationForm
     */
    public function getApplicationForm()
    {
        return $this->applicationForm;
    }

    /**
     * @param ApplicationForm $applicationForm
     * @return ApplicationForm
     */
    public function setApplicationForm($applicationForm)
    {
        $this->applicationForm = $applicationForm;
        return $this->applicationForm;
    }


}