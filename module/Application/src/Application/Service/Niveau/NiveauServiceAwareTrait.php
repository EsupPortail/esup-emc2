<?php

namespace Application\Service\Niveau;

trait NiveauServiceAwareTrait {

    /** @var NiveauService */
    private $niveauService;

    /**
     * @return NiveauService
     */
    public function getNiveauService(): NiveauService
    {
        return $this->niveauService;
    }

    /**
     * @param NiveauService $niveauService
     * @return NiveauService
     */
    public function setNiveauService(NiveauService $niveauService): NiveauService
    {
        $this->niveauService = $niveauService;
        return $this->niveauService;
    }
}