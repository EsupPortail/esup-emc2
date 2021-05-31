<?php

namespace Indicateur\Service\Indicateur;

trait IndicateurServiceAwareTrait {

    /** @var IndicateurService */
    private $indicateurService;

    /**
     * @return IndicateurService
     */
    public function getIndicateurService() : IndicateurService
    {
        return $this->indicateurService;
    }

    /**
     * @param IndicateurService $indicateurService
     * @return IndicateurService
     */
    public function setIndicateurService(IndicateurService $indicateurService) : IndicateurService
    {
        $this->indicateurService = $indicateurService;
        return $this->indicateurService;
    }


}