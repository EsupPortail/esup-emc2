<?php

namespace Formation\Service\StagiaireExterne;

trait StagiaireExterneServiceAwareTrait {

    private StagiaireExterneService $stagiaireExterneService;

    public function getStagiaireExterneService(): StagiaireExterneService
    {
        return $this->stagiaireExterneService;
    }

    public function setStagiaireExterneService(StagiaireExterneService $stagiaireExterneService): void
    {
        $this->stagiaireExterneService = $stagiaireExterneService;
    }

}