<?php

namespace FicheMetier\Service\FicheMetier;

trait FicheMetierServiceAwareTrait
{
    private FicheMetierService $ficheMetierService;

    public function getFicheMetierService() : FicheMetierService
    {
        return $this->ficheMetierService;
    }

    public function setFicheMetierService($ficheMetierService) : void
    {
        $this->ficheMetierService = $ficheMetierService;
    }
}

