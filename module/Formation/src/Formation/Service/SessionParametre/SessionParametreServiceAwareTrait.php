<?php

namespace Formation\Service\SessionParametre;

trait SessionParametreServiceAwareTrait {

    private SessionParametreService $sessionParametreService;

    /**
     * @return SessionParametreService
     */
    public function getSessionParametreService(): SessionParametreService
    {
        return $this->sessionParametreService;
    }

    /**
     * @param SessionParametreService $sessionParametreService
     */
    public function setSessionParametreService(SessionParametreService $sessionParametreService): void
    {
        $this->sessionParametreService = $sessionParametreService;
    }


}