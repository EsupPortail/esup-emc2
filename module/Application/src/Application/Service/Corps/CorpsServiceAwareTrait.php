<?php

namespace Application\Service\Corps;

trait CorpsServiceAwareTrait {

    /** @var CorpsService */
    private $corpsService;

    /**
     * @return CorpsService
     */
    public function getCorpsService()
    {
        return $this->corpsService;
    }

    /**
     * @param CorpsService $corpsService
     * @return CorpsService
     */
    public function setCorpsService($corpsService)
    {
        $this->corpsService = $corpsService;
        return $this->corpsService;
    }

}
