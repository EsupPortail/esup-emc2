<?php

namespace EntretienProfessionnel\Service\Ics;

trait IcsServiceAwareTrait {

    private IcsService $icsService;

    public function getIcsService(): IcsService
    {
        return $this->icsService;
    }

    public function setIcsService(IcsService $icsService): void
    {
        $this->icsService = $icsService;
    }

}