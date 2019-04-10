<?php

namespace Application\Service\EntretienProfessionnel;

trait EntretienProfessionnelServiceAwareTrait {

    /** @var EntretienProfessionnelService $entretienProfessionnelService */
    private $entretienProfessionnelService;

    /**
     * @return EntretienProfessionnelService
     */
    public function getEntretienProfessionnelService()
    {
        return $this->entretienProfessionnelService;
    }

    /**
     * @param EntretienProfessionnelService $entretienProfessionnelService
     * @return EntretienProfessionnelService
     */
    public function setEntretienProfessionnelService($entretienProfessionnelService)
    {
        $this->entretienProfessionnelService = $entretienProfessionnelService;
        return $this->entretienProfessionnelService;
    }


}