<?php

namespace Application\Form\FicheMetier;

trait ApplicationsFormAwareTrait {

    /** @var ApplicationsForm $applicationsForm */
    private $applicationsForm;

    /**
     * @return ApplicationsForm
     */
    public function getApplicationsForm()
    {
        return $this->applicationsForm;
    }

    /**
     * @param ApplicationsForm $applicationsForm
     * @return ApplicationsForm
     */
    public function setApplicationsForm($applicationsForm)
    {
        $this->applicationsForm = $applicationsForm;
        return $this->applicationsForm;
    }


}