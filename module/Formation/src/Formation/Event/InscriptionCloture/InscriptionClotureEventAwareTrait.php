<?php

namespace Formation\Event\InscriptionCloture;

trait InscriptionClotureEventAwareTrait
{
    private InscriptionClotureEvent $inscriptionClotureEvent;

    public function getInscriptionClotureEvent(): InscriptionClotureEvent
    {
        return $this->inscriptionClotureEvent;
    }

    public function setInscriptionClotureEvent(InscriptionClotureEvent $inscriptionClotureEvent): void
    {
        $this->inscriptionClotureEvent = $inscriptionClotureEvent;
    }

}