<?php

namespace Element\Form\ApplicationElement;

trait ApplicationElementFormAwareTrait {

    /** @var ApplicationElementForm */
    private $applicationElementForm;

    /**
     * @return ApplicationElementForm
     */
    public function getApplicationElementForm() :ApplicationElementForm
    {
        return $this->applicationElementForm;
    }

    /**
     * @param ApplicationElementForm $applicationElementForm
     * @return ApplicationElementForm
     */
    public function setApplicationElementForm(ApplicationElementForm $applicationElementForm) : ApplicationElementForm
    {
        $this->applicationElementForm = $applicationElementForm;
        return $this->applicationElementForm;
    }

}