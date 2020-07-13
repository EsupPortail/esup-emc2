<?php

namespace Application\Service\EntretienProfessionnel;

trait EntretienProfessionnelObservationServiceAwareTrait {

    /** @var EntretienProfessionnelObservationService */
    private $entretienProfessionnelObservationService;

    /**
     * @return EntretienProfessionnelObservationService
     */
    public function getEntretienProfessionnelObservationService()
    {
        return $this->entretienProfessionnelObservationService;
    }

    /**
     * @param EntretienProfessionnelObservationService $entretienProfessionnelObservationService
     * @return EntretienProfessionnelObservationService
     */
    public function setEntretienProfessionnelObservationService($entretienProfessionnelObservationService)
    {
        $this->entretienProfessionnelObservationService = $entretienProfessionnelObservationService;
        return $this->entretienProfessionnelObservationService;
    }

}