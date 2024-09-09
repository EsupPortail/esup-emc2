<?php

namespace Formation\Event\RappelAgent;

trait RappelAgentEventAwareTrait
{
    private RappelAgentEvent $rappelAgentEvent;

    public function getRappelAgentEvent(): RappelAgentEvent
    {
        return $this->rappelAgentEvent;
    }

    public function setRappelAgentEvent(RappelAgentEvent $rappelAgentEvent): void
    {
        $this->rappelAgentEvent = $rappelAgentEvent;
    }


}