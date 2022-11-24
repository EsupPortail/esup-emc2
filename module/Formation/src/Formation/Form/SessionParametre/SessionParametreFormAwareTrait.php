<?php

namespace Formation\Form\SessionParametre;

trait SessionParametreFormAwareTrait {

    private SessionParametreForm $sessionParametreForm;

    /**
     * @return SessionParametreForm
     */
    public function getSessionParametreForm(): SessionParametreForm
    {
        return $this->sessionParametreForm;
    }

    /**
     * @param SessionParametreForm $sessionParametreForm
     */
    public function setSessionParametreForm(SessionParametreForm $sessionParametreForm): void
    {
        $this->sessionParametreForm = $sessionParametreForm;
    }


}