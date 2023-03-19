<?php

namespace Carriere\Service\Corps;

trait CorpsServiceAwareTrait {

    private CorpsService $corpsService;

    public function getCorpsService() : CorpsService
    {
        return $this->corpsService;
    }

    public function setCorpsService(CorpsService $corpsService) : void
    {
        $this->corpsService = $corpsService;
    }

}
