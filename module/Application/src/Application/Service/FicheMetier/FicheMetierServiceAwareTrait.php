<?php

namespace Application\Service\FicheMetier;

trait FicheMetierServiceAwareTrait
{
    /**
     * @var FicheMetierService
     */
    private $ficheMetierService;

    /**
     * @return FicheMetierService
     */
    public function getFicheMetierService()
    {
        return $this->ficheMetierService;
    }

    /**
     * @param  FicheMetierService $ficheMetierService
     * @return FicheMetierService
     */
    public function setFicheMetierService($ficheMetierService)
    {
        $this->ficheMetierService = $ficheMetierService;
        return $ficheMetierService;
    }
}

