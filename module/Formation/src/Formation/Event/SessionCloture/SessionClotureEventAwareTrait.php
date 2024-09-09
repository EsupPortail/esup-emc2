<?php

namespace Formation\Event\SessionCloture;

trait SessionClotureEventAwareTrait
{
    private SessionClotureEvent $sessionClotureEvent;

    public function getSessionClotureEvent(): SessionClotureEvent
    {
        return $this->sessionClotureEvent;
    }

    public function setSessionClotureEvent(SessionClotureEvent $sessionClotureEvent): void
    {
        $this->sessionClotureEvent = $sessionClotureEvent;
    }

}