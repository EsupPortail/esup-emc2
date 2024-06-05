<?php

namespace Formation\Form\Session;

trait SessionFormAwareTrait {

    private SessionForm $sessionForm;

    public function getSessionForm(): SessionForm
    {
        return $this->sessionForm;
    }

    public function setSessionForm(SessionForm $sessionForm): void
    {
        $this->sessionForm = $sessionForm;
    }

}