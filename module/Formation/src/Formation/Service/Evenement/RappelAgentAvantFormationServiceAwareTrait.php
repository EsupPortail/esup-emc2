<?php

namespace Formation\Service\Evenement;


trait RappelAgentAvantFormationServiceAwareTrait {

    /** @var RappelAgentAvantFormationService */
    private $rappelAgentAvantFormationService;

    /**
     * @return RappelAgentAvantFormationService
     */
    public function getRappelAgentAvantFormationService(): RappelAgentAvantFormationService
    {
        return $this->rappelAgentAvantFormationService;
    }

    /**
     * @param RappelAgentAvantFormationService $rappelAgentAvantFormationService
     * @return RappelAgentAvantFormationService
     */
    public function setRappelAgentAvantFormationService(RappelAgentAvantFormationService $rappelAgentAvantFormationService): RappelAgentAvantFormationService
    {
        $this->rappelAgentAvantFormationService = $rappelAgentAvantFormationService;
        return $this->rappelAgentAvantFormationService;
    }

}