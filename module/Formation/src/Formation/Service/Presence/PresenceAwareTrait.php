<?php

namespace Formation\Service\Presence;

trait PresenceAwareTrait
{

    private PresenceService $presenceService;

    /**
     * @return PresenceService
     */
    public function getPresenceService(): PresenceService
    {
        return $this->presenceService;
    }

    /**
     * @param PresenceService $presenceService
     */
    public function setPresenceService(PresenceService $presenceService): void
    {
        $this->presenceService = $presenceService;
    }

}