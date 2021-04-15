<?php

namespace Formation\Service\Stagiaire;

trait StagiaireServiceAwareTrait {

    /** @var StagiaireService */
    private $stagiaireService;

    /**
     * @return StagiaireService
     */
    public function getStagiaireService(): StagiaireService
    {
        return $this->stagiaireService;
    }

    /**
     * @param StagiaireService $stagiaireService
     * @return StagiaireService
     */
    public function setStagiaireService(StagiaireService $stagiaireService): StagiaireService
    {
        $this->stagiaireService = $stagiaireService;
        return $this->stagiaireService;
    }


}