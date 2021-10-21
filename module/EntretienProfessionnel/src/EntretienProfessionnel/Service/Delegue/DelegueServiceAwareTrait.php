<?php

namespace EntretienProfessionnel\Service\Delegue;

trait DelegueServiceAwareTrait {

    /** @var DelegueService */
    private $delegueService;

    /**
     * @return DelegueService
     */
    public function getDelegueService(): DelegueService
    {
        return $this->delegueService;
    }

    /**
     * @param DelegueService $delegueService
     * @return DelegueService
     */
    public function setDelegueService(DelegueService $delegueService): DelegueService
    {
        $this->delegueService = $delegueService;
        return $this->delegueService;
    }

}