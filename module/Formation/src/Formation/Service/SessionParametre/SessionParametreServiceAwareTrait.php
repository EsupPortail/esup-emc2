<?php

namespace Formation\Service\SessionParametre;

trait SessionParametreServiceAwareTrait
{

    private SessionParametreService $sessionParametreService;

    public function getSessionParametreService(): SessionParametreService
    {
        return $this->sessionParametreService;
    }

    public function setSessionParametreService(SessionParametreService $sessionParametreService): void
    {
        $this->sessionParametreService = $sessionParametreService;
    }


}