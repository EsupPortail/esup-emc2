<?php

namespace Carriere\Service\Mobilite;

trait MobiliteServiceAwareTrait {

    private MobiliteService $mobiliteService;

    public function getMobiliteService() : MobiliteService
    {
        return $this->mobiliteService;
    }

    public function setMobiliteService(MobiliteService $mobiliteService) : void
    {
        $this->mobiliteService = $mobiliteService;
    }

}