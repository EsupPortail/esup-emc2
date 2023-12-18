<?php

namespace Formation\Service\Inscription;

trait InscriptionServiceAwareTrait
{
    private InscriptionService $inscriptionService;

    public function getInscriptionService(): InscriptionService
    {
        return $this->inscriptionService;
    }

    public function setInscriptionService(InscriptionService $inscriptionService): void
    {
        $this->inscriptionService = $inscriptionService;
    }

}