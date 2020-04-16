<?php

namespace Application\Service\Synchro;

trait SynchroServiceAwareTrait {

    /** @var SynchroService */
    private $synchroService;

    /**
     * @return SynchroService
     */
    public function getSynchroService()
    {
        return $this->synchroService;
    }

    /**
     * @param SynchroService $synchroService
     * @return SynchroService
     */
    public function setSynchroService(SynchroService $synchroService)
    {
        $this->synchroService = $synchroService;
        return $this->synchroService;
    }


}