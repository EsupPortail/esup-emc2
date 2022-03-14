<?php

namespace Structure\Service\StructureAgentForce;

trait StructureAgentForceServiceAwareTrait {

    /** @var StructureAgentForceService */
    private $structureAgentForceService;

    /**
     * @return StructureAgentForceService
     */
    public function getStructureAgentForceService(): StructureAgentForceService
    {
        return $this->structureAgentForceService;
    }

    /**
     * @param StructureAgentForceService $structureAgentForceService
     * @return StructureAgentForceService
     */
    public function setStructureAgentForceService(StructureAgentForceService $structureAgentForceService): StructureAgentForceService
    {
        $this->structureAgentForceService = $structureAgentForceService;
        return $this->structureAgentForceService;
    }


}