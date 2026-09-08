<?php

namespace Application\Service\Util;

trait UtilServiceAwareTrait {

    private UtilService $utilService;

    public function getUtilService(): UtilService
    {
        return $this->utilService;
    }

    public function setUtilService(UtilService $utilService): void
    {
        $this->utilService = $utilService;
    }


}
