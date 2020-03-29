<?php

namespace Application\Service\Correspondance;

trait CorrespondanceServiceAwareTrait {

    /** @var CorrespondanceService */
    private $correspondanceService;

    /**
     * @return CorrespondanceService
     */
    public function getCorrespondanceService()
    {
        return $this->correspondanceService;
    }

    /**
     * @param CorrespondanceService $correspondanceService
     * @return CorrespondanceService
     */
    public function setCorrespondanceService($correspondanceService)
    {
        $this->correspondanceService = $correspondanceService;
        return $this->correspondanceService;
    }

}
