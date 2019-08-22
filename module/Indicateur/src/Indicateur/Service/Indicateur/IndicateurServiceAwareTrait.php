<?php

namespace Indicateur\Service\Indicateur;

trait IndicateurServiceAwareTrait {

    /** @var IndicateurService */
    private $indicateurService;

    /**
     * @return IndicateurService
     */
    public function getIndicateurService()
    {
        return $this->indicateurService;
    }

    /**
     * @param IndicateurService $indicateurService
     * @return IndicateurService
     */
    public function setIndicateurService($indicateurService)
    {
        $this->indicateurService = $indicateurService;
        return $this->indicateurService;
    }


}