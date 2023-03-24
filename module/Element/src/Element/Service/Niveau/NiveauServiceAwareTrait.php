<?php

namespace Element\Service\Niveau;

trait NiveauServiceAwareTrait {

    private NiveauService $niveauService;

    public function getNiveauService(): NiveauService
    {
        return $this->niveauService;
    }

    public function setNiveauService(NiveauService $niveauService): void
    {
        $this->niveauService = $niveauService;
    }


}