<?php

namespace Structure\Service\StructureGestionnaire;

trait StructureGestionnaireServiceAwareTrait
{

    private StructureGestionnaireService $structureGestionnaireService;

    public function getStructureGestionnaireService(): StructureGestionnaireService
    {
        return $this->structureGestionnaireService;
    }

    public function setStructureGestionnaireService(StructureGestionnaireService $structureGestionnaireService): void
    {
        $this->structureGestionnaireService = $structureGestionnaireService;
    }


}
