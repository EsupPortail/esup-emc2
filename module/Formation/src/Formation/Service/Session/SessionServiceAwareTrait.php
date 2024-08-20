<?php

namespace Formation\Service\Session;

trait SessionServiceAwareTrait
{

    private SessionService $sessionService;

    public function getSessionService(): SessionService
    {
        return $this->sessionService;
    }

    public function setSessionService(SessionService $service): void
    {
        $this->sessionService = $service;
    }
}