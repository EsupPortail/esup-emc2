<?php

namespace Carriere\Service\EmploiType;

trait EmploiTypeServiceAwareTrait {

    private EmploiTypeService $emploiTypeService;

    public function getEmploiTypeService() : EmploiTypeService
    {
        return $this->emploiTypeService;
    }

    public function setEmploiTypeService(EmploiTypeService $emploiTypeService) : void
    {
        $this->emploiTypeService = $emploiTypeService;
    }

}
