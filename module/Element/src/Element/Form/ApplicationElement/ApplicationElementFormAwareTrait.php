<?php

namespace Element\Form\ApplicationElement;

trait ApplicationElementFormAwareTrait {

    private ApplicationElementForm $applicationElementForm;

    public function getApplicationElementForm() :ApplicationElementForm
    {
        return $this->applicationElementForm;
    }

    public function setApplicationElementForm(ApplicationElementForm $applicationElementForm) : void
    {
        $this->applicationElementForm = $applicationElementForm;
    }

}