<?php

namespace Carriere\Service\Correspondance;

trait CorrespondanceServiceAwareTrait {

    private CorrespondanceService $correspondanceService;

    public function getCorrespondanceService() : CorrespondanceService
    {
        return $this->correspondanceService;
    }

    public function setCorrespondanceService(CorrespondanceService $correspondanceService) : void
    {
        $this->correspondanceService = $correspondanceService;
    }

}
