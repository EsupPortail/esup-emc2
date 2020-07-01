<?php

namespace Application\Service\FicheMetierEtat;

trait FicheMetierEtatServiceAwareTrait {

    /** @var FicheMetierEtatService */
    private $ficheMetierEtatService;

    /**
     * @return FicheMetierEtatService
     */
    public function getFicheMetierEtatService()
    {
        return $this->ficheMetierEtatService;
    }

    /**
     * @param FicheMetierEtatService $ficheMetierEtatService
     * @return FicheMetierEtatService
     */
    public function setFicheMetierEtatService($ficheMetierEtatService)
    {
        $this->ficheMetierEtatService = $ficheMetierEtatService;
        return $this->ficheMetierEtatService;
    }


}