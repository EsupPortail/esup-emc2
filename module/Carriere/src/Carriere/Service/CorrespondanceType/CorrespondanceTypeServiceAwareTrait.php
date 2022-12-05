<?php

namespace Carriere\Service\CorrespondanceType;

trait CorrespondanceTypeServiceAwareTrait {

    private CorrespondanceTypeService $correspondanceTypeService;

    public function getCorrespondanceTypeService(): CorrespondanceTypeService
    {
        return $this->correspondanceTypeService;
    }

    public function setCorrespondanceTypeService(CorrespondanceTypeService $correspondanceTypeService): void
    {
        $this->correspondanceTypeService = $correspondanceTypeService;
    }


}