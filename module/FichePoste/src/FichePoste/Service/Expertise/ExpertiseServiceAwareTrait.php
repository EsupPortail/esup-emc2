<?php

namespace FichePoste\Service\Expertise;

trait ExpertiseServiceAwareTrait {

    private ExpertiseService $expertiseService;

    public function getExpertiseService(): ExpertiseService
    {
        return $this->expertiseService;
    }

    public function setExpertiseService(ExpertiseService $expertiseService): void
    {
        $this->expertiseService = $expertiseService;
    }
}
