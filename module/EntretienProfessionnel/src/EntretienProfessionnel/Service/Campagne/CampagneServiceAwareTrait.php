<?php

namespace EntretienProfessionnel\Service\Campagne;

trait CampagneServiceAwareTrait {

    /** @var CampagneService */
    private $campagneService;

    /**
     * @return CampagneService
     */
    public function getCampagneService()
    {
        return $this->campagneService;
    }

    /**
     * @param CampagneService $campagneService
     * @return CampagneService
     */
    public function setCampagneService(CampagneService $campagneService) : CampagneService
    {
        $this->campagneService = $campagneService;
        return $this->campagneService;
    }


}