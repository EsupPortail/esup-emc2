<?php

namespace Application\Service\Evenement;

trait SynchroOctopusServiceAwareTrait {

    /** @var SynchroOctopusService */
    private $synchroOctopusService;

    /**
     * @return SynchroOctopusService
     */
    public function getSynchroOctopusService (): SynchroOctopusService
    {
        return $this->synchroOctopusService;
    }

    /**
     * @param SynchroOctopusService $synchroOctopusService
     * @return SynchroOctopusService
     */
    public function setSynchroOctopusService (SynchroOctopusService $synchroOctopusService): SynchroOctopusService
    {
        $this->synchroOctopusService = $synchroOctopusService;
        return $this->synchroOctopusService;
    }
}