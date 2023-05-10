<?php

namespace Structure\Service\Structure;

trait StructureServiceAwareTrait {

    private StructureService $structureService;

    public function getStructureService() : StructureService
    {
        return $this->structureService;
    }

    public function setStructureService(StructureService $structureService) : void
    {
        $this->structureService = $structureService;
    }

}