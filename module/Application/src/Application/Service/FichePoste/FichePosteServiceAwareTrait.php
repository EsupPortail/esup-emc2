<?php

namespace Application\Service\FichePoste;

Trait FichePosteServiceAwareTrait {

    private FichePosteService $fichePosteService;

    public function getFichePosteService(): FichePosteService
    {
        return $this->fichePosteService;
    }

    public function setFichePosteService(FichePosteService $fichePosteService): void
    {
        $this->fichePosteService = $fichePosteService;
    }
}