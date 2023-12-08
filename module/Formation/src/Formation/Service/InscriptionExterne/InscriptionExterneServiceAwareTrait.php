<?php

namespace Formation\Service\InscriptionExterne;

trait InscriptionExterneServiceAwareTrait
{
    private InscriptionExterneService $inscriptionExterneService;

    public function getInscriptionExterneService(): InscriptionExterneService
    {
        return $this->inscriptionExterneService;
    }

    public function setInscriptionExterneService(InscriptionExterneService $inscriptionExterneService): void
    {
        $this->inscriptionExterneService = $inscriptionExterneService;
    }

}