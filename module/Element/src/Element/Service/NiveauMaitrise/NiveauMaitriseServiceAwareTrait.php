<?php

namespace Element\Service\NiveauMaitrise;

trait NiveauMaitriseServiceAwareTrait {

    private NiveauMaitriseService $niveauMaitriseService;

    public function getNiveauMaitriseService(): NiveauMaitriseService
    {
        return $this->niveauMaitriseService;
    }

    public function setNiveauMaitriseService(NiveauMaitriseService $niveauMaitriseService): void
    {
        $this->niveauMaitriseService = $niveauMaitriseService;
    }


}