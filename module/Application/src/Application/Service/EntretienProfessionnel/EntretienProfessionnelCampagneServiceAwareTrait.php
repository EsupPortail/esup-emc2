<?php

namespace Application\Service\EntretienProfessionnel;

trait EntretienProfessionnelCampagneServiceAwareTrait {

    /** @var EntretienProfessionnelCampagneService */
    private $entretienProfessionnelCampagneService;

    /**
     * @return EntretienProfessionnelCampagneService
     */
    public function getEntretienProfessionnelCampagneService()
    {
        return $this->entretienProfessionnelCampagneService;
    }

    /**
     * @param EntretienProfessionnelCampagneService $entretienProfessionnelCampagneService
     * @return EntretienProfessionnelCampagneService
     */
    public function setEntretienProfessionnelCampagneService(EntretienProfessionnelCampagneService $entretienProfessionnelCampagneService)
    {
        $this->entretienProfessionnelCampagneService = $entretienProfessionnelCampagneService;
        return $this->entretienProfessionnelCampagneService;
    }


}