<?php

namespace Application\Service\FichePoste;

Trait FichePosteServiceAwareTrait {

    /** @var FichePosteService */
    private $fichePosteService;

    /**
     * @return FichePosteService
     */
    public function getFichePosteService()
    {
        return $this->fichePosteService;
    }

    /**
     * @param FichePosteService $fichePosteService
     * @return FichePosteService
     */
    public function setFichePosteService($fichePosteService)
    {
        $this->fichePosteService = $fichePosteService;
        return $this->fichePosteService;
    }
}