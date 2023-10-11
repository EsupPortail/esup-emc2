<?php

namespace Formation\Service\Evenement;


trait RappelAgentAvantFormationServiceAwareTrait {

    private RappelAgentAvantFormationService $rappelAgentAvantFormationService;

    public function getRappelAgentAvantFormationService(): RappelAgentAvantFormationService
    {
        return $this->rappelAgentAvantFormationService;
    }

    public function setRappelAgentAvantFormationService(RappelAgentAvantFormationService $rappelAgentAvantFormationService): void
    {
        $this->rappelAgentAvantFormationService = $rappelAgentAvantFormationService;
    }

}