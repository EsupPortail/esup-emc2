<?php

namespace Carriere\Service\Correspondance;

trait CorrespondanceServiceAwareTrait {

    /** @var CorrespondanceService */
    private $correspondanceService;

    /**
     * @return CorrespondanceService
     */
    public function getCorrespondanceService() : CorrespondanceService
    {
        return $this->correspondanceService;
    }

    /**
     * @param CorrespondanceService $correspondanceService
     * @return CorrespondanceService
     */
    public function setCorrespondanceService(CorrespondanceService $correspondanceService) : CorrespondanceService
    {
        $this->correspondanceService = $correspondanceService;
        return $this->correspondanceService;
    }

}
