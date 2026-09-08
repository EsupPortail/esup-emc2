<?php

namespace Structure\Service\StructureResponsable;

trait StructureResponsableServiceAwareTrait
{

    private StructureResponsableService $structureResponsableService;

    public function getStructureResponsableService(): StructureResponsableService
    {
        return $this->structureResponsableService;
    }

    public function setStructureResponsableService(StructureResponsableService $structureResponsableService): void
    {
        $this->structureResponsableService = $structureResponsableService;
    }


}
