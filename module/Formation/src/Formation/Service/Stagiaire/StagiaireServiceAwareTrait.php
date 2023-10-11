<?php

namespace Formation\Service\Stagiaire;

trait StagiaireServiceAwareTrait {

    private StagiaireService $stagiaireService;

    public function getStagiaireService(): StagiaireService
    {
        return $this->stagiaireService;
    }

    public function setStagiaireService(StagiaireService $stagiaireService): void
    {
        $this->stagiaireService = $stagiaireService;
    }


}