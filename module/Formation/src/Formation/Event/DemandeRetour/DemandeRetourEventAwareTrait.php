<?php

namespace Formation\Event\DemandeRetour;

trait DemandeRetourEventAwareTrait
{
    private DemandeRetourEvent $demandeRetourEvent;

    public function getDemandeRetourEvent(): DemandeRetourEvent
    {
        return $this->demandeRetourEvent;
    }

    public function setDemandeRetourEvent(DemandeRetourEvent $demandeRetourEvent): void
    {
        $this->demandeRetourEvent = $demandeRetourEvent;
    }

}