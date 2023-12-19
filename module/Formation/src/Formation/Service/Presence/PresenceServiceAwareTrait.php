<?php

namespace Formation\Service\Presence;

trait PresenceServiceAwareTrait
{

    private PresenceService $presenceService;

    public function getPresenceService(): PresenceService
    {
        return $this->presenceService;
    }

    public function setPresenceService(PresenceService $presenceService): void
    {
        $this->presenceService = $presenceService;
    }

}