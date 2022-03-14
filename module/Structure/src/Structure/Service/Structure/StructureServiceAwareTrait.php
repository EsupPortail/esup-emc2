<?php

namespace Structure\Service\Structure;

trait StructureServiceAwareTrait {

    /** @var StructureService */
    private $structureService;

    /**
     * @return StructureService
     */
    public function getStructureService()
    {
        return $this->structureService;
    }

    /**
     * @param StructureService $structureService
     * @return StructureService
     */
    public function setStructureService($structureService)
    {
        $this->structureService = $structureService;
        return $this->structureService;
    }


}