<?php

namespace Carriere\Service\Corps;

trait CorpsServiceAwareTrait {

    /** @var CorpsService */
    private $corpsService;

    /**
     * @return CorpsService
     */
    public function getCorpsService() : CorpsService
    {
        return $this->corpsService;
    }

    /**
     * @param CorpsService $corpsService
     * @return CorpsService
     */
    public function setCorpsService(CorpsService $corpsService) : CorpsService
    {
        $this->corpsService = $corpsService;
        return $this->corpsService;
    }

}
