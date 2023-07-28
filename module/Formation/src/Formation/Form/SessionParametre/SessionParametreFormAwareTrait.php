<?php

namespace Formation\Form\SessionParametre;

trait SessionParametreFormAwareTrait {

    private SessionParametreForm $sessionParametreForm;

    public function getSessionParametreForm(): SessionParametreForm
    {
        return $this->sessionParametreForm;
    }

    public function setSessionParametreForm(SessionParametreForm $sessionParametreForm): void
    {
        $this->sessionParametreForm = $sessionParametreForm;
    }


}